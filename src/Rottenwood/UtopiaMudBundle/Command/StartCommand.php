<?php
/**
 * Author: Rottenwood
 * Date Created: 06.07.14 13:28
 */

namespace Rottenwood\UtopiaMudBundle\Command;

use Rottenwood\UtopiaMudBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Thruway\ClientSession;
use Thruway\Connection;

class StartCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('mud:start')
            ->setDescription('Start Utopia MUD server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

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
            $clients = new Entity\DataChannel();

            // Подписка на канал данных и коллбэк при их получении
            $session->subscribe('system.channel', function ($args) use ($session, $clients) {

                echo "Данные: {$args[0]}\n";

                // Если пришел хэш доступа
                if (strpos($args[0], 'HASH:::') !== false) {
                    $hash = substr($args[0], 7);
                    // Проверка хэша на уникальность
                    if ($clients->hashIsUnique($hash)) {
                        // Если хэш уже присутствует
                        echo "Переподключение хэша: \033[0;33m", $hash, "\033[m\n";
                    } else {
                        // Если хэш отсутствует
                        echo "Зарегистрирован новый хэш: \033[1;33m", $hash, "\033[m\n";

                        $char = $this->getContainer()->get('doctrine.orm.entity_manager')->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);

                        // Добавление клиента в список подключенных клиентов
                        $clients->add($hash, $char[0]);

                        // Подключение к каналу пользователя
                        $channel = 'personal.' . $hash;

                        // Обработка персонального канала данных пользователя
                        $personalChannel = function ($argss) use ($hash, $session, $channel, $clients) {
                            echo "\033[0;37m{$clients->clients[$hash]->getUsername()} \033[1;34m[{$argss[0]}]\033[0;37m {$argss[1]}\033[m ";
                            // Распознание типа запроса
                            if ($argss[0] == "CMD") {
                                // Передача команды и объекта текущего Player командному сервису
                                $result = $this->getContainer()->get('command')->execute($argss[1], $clients->clients[$hash]);
                                // Публикация результата в персональный канал данных
                                $session->publish($channel, $result);
                            } else {
                                echo "\033[1;31m[Ошибка]\033[m Запрос не распознан!\n";
                            }
                        };

                        // Подписка на персональный канал данных пользователя
                        $session->subscribe($channel, $personalChannel);

                    };

                    // Отправка стартовых команд пользователю
                    $onlogin = $result = $this->getContainer()->get('command')->execute("look",
                        $clients->clients[$hash]);
                    $channel = 'personal.' . $hash;
                    $session->publish($channel, $onlogin);
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
        });

        $connection->open();
    }
}