<?php

$container->set('config_bd',function(){
    return (object)[
        "server" => $_ENV["DB_HOST"],
        "database" => $_ENV["DB_NAME"],
        "username" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSW"],
     ];  
});
