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
            return true;
        } else {
            return false;
        }
    }
}

/// Консольные цвета
$color = array(
    "red"    => "\033[0;31m",
    "lred"   => "\033[1;31m",
    "green"  => "\033[0;32m",
    "lgreen" => "\033[1;32m",
    "blue"   => "\033[0;34m",
    "lblue"  => "\033[1;34m",
    "yellow"  => "\033[0;33m",
    "lyellow"  => "\033[1;33m",
    "white"  => "\033[1;37m",
    "cyan"  => "\033[0;36m",
    "lcyan"  => "\033[1;36m",
    "mag"  => "\033[0;35m'",
    "lmag"  => "\033[1;35m",
    "gray"  => "\033[0;37m",
    "dgray"  => "\033[1;30m",
    "normal" => "\033[m"
);

///

$onClose = function ($msg) {
    echo $msg;
};

$connection = new Connection(
    array(
        "realm"   => 'utopia',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:6661',
    )
);

$connection->on('open', function (ClientSession $session) use ($connection) {

        // Создаю коллекцию подписчиков
        $clients = new Clients();




        // Подписка на канал данных и коллбэк при их получении
        $session->subscribe('system.channel', function ($args) use ($session, $clients, $personalChannel) {

            echo "Данные: {$args[0]}\n";

            // Если пришел хэш доступа
            if (strpos($args[0], 'HASH:::') !== false) {
                $hash = substr($args[0], 7);
                // Проверка хэша на уникальность
                if ($clients->clientIsUnique($hash)) {
                    // Если хэш уже присутствует
                    echo "Переподключение хэша: \033[0;33m", $hash, "\033[m\n";
                } else {
                    // Если хэш отсутствует
                    echo "Зарегистрирован новый хэш: \033[1;33m", $hash, "\033[m\n";
                    $clients->add($hash);
                    // Подключение к каналу пользователя
                    $channel = 'personal.' . $hash;

                    // Обработка персонального канала данных пользователя
                    $personalChannel = function ($argss) use ($hash, $session, $channel) {
                        echo "\033[0;37m{$hash} \033[1;34m[{$argss[0]}]\033[0;37m {$argss[1]}\033[m\n";
                        // Если пришла команда
                        if ($argss[0] == "CMD") {
                            echo "Command get!!\n";
                            $session->publish($channel, ["message" => "0:1"]);
                        } else {
                            echo "\033[1;31m[Ошибка]\033[m Запрос не распознан!\n";
                        }
                    };

                    // Подписка на персональный канал данных пользователя
                    $session->subscribe($channel, $personalChannel);

                };
            };


        });


        // Публикация в канал данных
        $session->publish('system.channel', array('Сервер перезагружен.'), [], ["acknowledge" => true])->then(
            function () {
                echo "\n     \033[1;30m>==<   \033[1;31mУтопия   \033[1;30m>==<\n\n";
                echo "     \033[1;30m    www.utopia.ml\n";
                echo "     \033[1;30m     Rottenwood\n";
                echo "     \033[1;30m        2014\n\n";
                echo "     \033[1;30m====================\033[m\n\n";
            },
            function ($error) {
                echo "\033[1;31mОшибка отправки данных: {$error}\033[m\n";
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