<?php
/**
 * Author: Rottenwood
 * Date Created: 05.07.14 16:36
 */

//TODO: сделать запуск сервера отдельной симфони-командой

use Thruway\ClientSession;
use Thruway\Connection;

require_once __DIR__ . '/../vendor/autoload.php';

$onClose = function ($msg) {
    echo $msg;
};

$connection = new Connection(
    array(
        "realm"   => 'realm1',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:6661',
    )
);

$connection->on('open', function (ClientSession $session) {

        // 1) subscribe to a topic
        $onevent = function ($args) {
            echo "Event {$args[0]}\n";
        };
        $session->subscribe('test.channel', $onevent);

        // 2) publish an event
        $session->publish('test.channel', array('Hello, world from PHP!!!'), [], ["acknowledge" => true])->then(
            function () {
                echo "Publish Acknowledged!\n";
            },
            function ($error) {
                // publish failed
                echo "Publish Error {$error}\n";
            }
        );

        // 3) register a procedure for remoting
        $add2 = function ($args) {
            return $args[0] + $args[1];
        };
        $session->register('server.add', $add2);

        // 4) call a remote procedure
        $session->call('server.add', array(2, 3))->then(
            function ($res) {
                echo "Result: {$res}\n";
            },
            function ($error) {
                echo "Call Error: {$error}\n";
            }
        );
    }

);

$connection->open();