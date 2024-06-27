<?php

namespace App\controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Container\ContainerInterface;
use PDO;

class BancoComercial
{
    protected $container;
    public function __construct(ContainerInterface $c)
    {
        $this->container = $c;
    }

    function actualizarSaldos(Request $request, Response $response)
    {
        $body = json_decode($request->getBody());
        $sql = "EXEC actualizarSaldosBancosCom :idBancoOrigen, :idBancoDestino, :monto";
        $con = $this->container->get('bd');
        $query = $con->prepare($sql);
        $query->bindValue(':idBancoOrigen', $body->idBancoOrigen, PDO::PARAM_INT);
        $query->bindValue(':idBancoDestino', $body->idBancoDestino, PDO::PARAM_INT);
        $query->bindValue(':monto', $body->monto, PDO::PARAM_STR);
        $query->execute();

        try {
            $res = $query->fetchAll();
        } catch (\Throwable $th) {
            $res = [[]];
            $res[0]['estado'] = '1';
            $res[0]['mensaje'] = 'Se completo la transaccion correctamente';
            $res = json_decode(json_encode($res));
        }
        
        $status = match ($res[0]->estado) {
            '0' => 400,
            '1' => 200,
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
}
