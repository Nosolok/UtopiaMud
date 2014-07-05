<?php
/**
 * Author: Rottenwood
 * Date Created: 05.07.14 16:36
 */

//TODO: сделать запуск сервера отдельной симфони-командой

use Rottenwood\UtopiaMudBundle\Server;
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

        // Подписка на канал данных и коллбэк при их получении
        $session->subscribe('test.channel', function ($args) {
            echo "Данные: {$args[0]}\n";

        });

        // Публикация в канал данных
        $session->publish('test.channel', array('Сервер перезагружен.'), [], ["acknowledge" => true])->then(
            function () {
                echo "Данные отправлены!\n";
            },
            function ($error) {
                echo "Ошибка отправки данных: {$error}\n";
            }
        );

        // Создание нового канала для каждого игрока
        

//        // Назначение функции для удаленного выполнение
//        $add2 = function ($args) {
//            return $args[0] + $args[1];
//        };
//        $session->register('server.add', $add2);
//
//        // Выполнение функции на удаленной стороне (клиенте)
//        $session->call('server.add', array(2, 3))->then(
//            function ($res) {
//                echo "Удаленный вызов: {$res}\n";
//            },
//            function ($error) {
//                echo "Ошибка удаленного вызова: {$error}\n";
//            }
//        );
    }

);

$connection->open();