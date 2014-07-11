<?php

if (file_exists(__DIR__.'/../../../autoload.php')) {
    require __DIR__.'/../../../autoload.php';
} else {
    require __DIR__.'/../vendor/autoload.php';
}

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router();

$transportProvider = new RatchetTransportProvider("0.0.0.0", 6661);

$router->addTransportProvider($transportProvider);

$router->start();