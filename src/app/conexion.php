<?php

use Psr\Container\ContainerInterface;

$container->set('bd', function (ContainerInterface $c) {

    $config = $c->get("config_bd");

    $opc = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
    ];

    $dsn = "sqlsrv:Server={$config->server};Database={$config->database}";

    try {
        $conexion = new PDO($dsn, $config->username, $config->password, $opc);
    } catch (PDOException $e) {
        print "error" . $e->getMessage() . "<br>";
        die();
    }
    return $conexion;
});