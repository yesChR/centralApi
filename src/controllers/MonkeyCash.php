<?php

namespace App\controllers;

use DI\Container;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use PDO;

class MonkeyCash
{
    protected $container;
    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    function create(Request $request, Response $response){
        $body = json_decode($request->getBody());
        $sql = "EXEC insertarNuevoCliente :telefono, :idCliente, :idBanco";
        $con = $this->container->get('bd');
        $query = $con->prepare($sql);
        $query->bindValue(':telefono', $body->telefono, PDO::PARAM_STR);
        $query->bindValue(':idCliente', $body->idCliente, PDO::PARAM_STR);
        $query->bindValue(':idBanco', $body->idBanco, PDO::PARAM_INT);
        $query->execute();
        try {
            $res = $query->fetchAll();
        } catch (\Throwable $th) {
            $res = [[]];
            $res[0]['estado'] = '1';
            $res[0]['mensaje'] = 'Se inserto correctamente';
            $res = json_decode(json_encode($res));
        }

        $status = match ($res[0]->estado) {
            '0' => 409,
            '1' => 201,
            '2' => 404,
            '3' => 400,
        };
        $query = null;
        $con = null;
        $response->getBody()->write(json_encode($res));
        return $response
            ->withHeader('Content-type', 'Application/json')
            ->withStatus($status);
    }
    
    function validarCuenta(Request $request, Response $response, $args)
    {
        $sql = "EXEC validarCuenta :telefono";
        $con = $this->container->get('bd');
        $query = $con->prepare($sql);
        $query->bindValue(':telefono', $args['telefono'], PDO::PARAM_STR);
        $query->execute();
        $res = $query->fetchAll();
        $query = null;
        $con = null;
        if(isset($res[0]->estado)){
            $response->getBody()->write(json_encode($res));
            $status = 204;
        }else{
            $response->getBody()->write(json_encode($res));
            $status = 200;
        }
        return $response
            ->withHeader('Content-type', 'Application/json')
            ->withStatus($status);
    }
}
