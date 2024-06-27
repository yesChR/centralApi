<?php
namespace App\controllers;
use Slim\Routing\RouteCollectorProxy;

$app->group('/monkeycash',function(RouteCollectorProxy $monkeycash){
    $monkeycash->get('/{telefono}', MonkeyCash::class . ':validarCuenta');
    $monkeycash->post('', MonkeyCash::class . ':create');
});

$app->group('/bancoComercial',function(RouteCollectorProxy $bancoComercial){
    $bancoComercial->put('/actualizarSaldos', BancoComercial::class . ':actualizarSaldos');
});

