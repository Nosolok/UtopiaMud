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

// Класс для хранения списка подключенных игроков
//TODO: переместить класс в отдельный файл
class Clients {
    public $clients = array();

    public function add($client) {
        $this->clients[] = $client;
    }

    public function clientIsUnique($client) {

        if (in_array($client, $this->clients)) {
            echo "YES \n";
            return true;
        } else {
            echo "NO \n";
            return false;
        }
    }
}

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

$connection->on('open', function (ClientSession $session) use ($connection) {

        // Создаю коллекцию подписчиков
        $clients = new Clients();


        // Подписка на канал данных и коллбэк при их получении
        $session->subscribe('system.channel', function ($args) use ($session, $clients) {

            echo "Данные: {$args[0]}\n";

            // Если пришел хэш доступа
            if (strpos($args[0], 'HASH:::') !== false) {
                $hash = substr($args[0], 7);
                // Проверяю его на уникальность
                if ($clients->clientIsUnique($hash)) {
                    // Если хэш уже присутствует
                    echo 'Переподключение хэша: ', $hash, "\n";
                } else {
                    // Если хэш отсутствует
                    echo 'Зарегистрирован новый хэш: ', $hash, "\n";
                    $clients->add($hash);
                    // Подключение к каналу пользователя
                    $channel = 'personal.' . $hash;
                    $session->subscribe($channel, function ($argss) use ($hash) {
                        echo "Личные данные: [{$hash}] {$argss[0]}\n";
                    });
                };
            };


        });


        // Публикация в канал данных
        $session->publish('system.channel', array('Сервер перезагружен.'), [], ["acknowledge" => true])->then(
            function () {
                echo "Данные отправлены!\n";
            },
            function ($error) {
                echo "Ошибка отправки данных: {$error}\n";
            }
        );

        // Создание нового канала для отдельного игрока


        //        // Назначение функции для удаленного выполнения
        //        $session->register('server.createchannel', function ($args) use ($session) {
        //            $name = $args[0];
        //            $channel = 'personal.' . $name;
        //            $session->subscribe($channel, function ($argss) use ($name) {
        //                echo "Личные данные: {$name} {$argss[0]}\n";
        //
        //            });
        //        });

        //        // Назначение функции для удаленного выполнения
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