<?php
/**
 * Author: Rottenwood
 * Date Created: 05.07.14 16:22
 */

//TODO: сделать запуск сервера отдельной симфони-командой

require_once __DIR__.'/../vendor/autoload.php';

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router();

$transportProvider = new RatchetTransportProvider("127.0.0.1", 6661);

$router->addTransportProvider($transportProvider);

$router->start();
