<?php
/**
 * Author: Rottenwood
 * Date Created: 16.07.14 1:21
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\DependencyInjection\Container;

class WebsocketPusherService implements WampServerInterface {

    private $container;
    private $clients;
    private $em;
    private $onlineChars;
    private $topics = array();

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        // Создаю коллекцию подписчиков
        $this->clients = $this->container->get('datachannel');
        $this->onlineChars = new \SplObjectStorage;
//        $this->topics = new \SplObjectStorage;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $channel = $topic->getId();
        echo "Подписка на $channel\n";

        // Запись канала в список
        $this->topics[$channel] = $topic;

    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->onlineChars->attach($conn);
        echo "Новое соединение! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // Удаление вышедшего клиента из списка юзеров
        $whoQuits = $this->onlineChars->offsetGet($conn);
        $this->clients->remove($whoQuits);
        $this->onlineChars->detach($conn);
        echo "Соединение {$conn->resourceId} разорвано\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $channel = $topic->getId();

        // Если сообщение пришло в персональный канал
        if ((substr($channel, 0, 9) == 'personal.')) {
            $hash = substr($channel, 9);
            /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $char */
            $char = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);
            $char = $char[0];
            $channel = $char->getUsername();

            // Если передана команда
            if (array_key_exists("CMD", $event)) {
                echo "$channel [CMD] ";

                $command = $event["CMD"];
                $result = $this->container->get('command')->execute($command, $char);

//                // Отправка результата клиенту
//                $topic->broadcast($result);

                // Отправка результата связанным участникам
                if (array_key_exists("3rd", $result)) {
                    // Список респондентов
                    $whomToTell = $result["3rd"];

                    // Сообщение для респондентов
                    $thirdEcho = $result["3rdecho"];
                    unset($result["3rd"]);
                    unset($result["3rdecho"]);

                    // Поиск каналов данных нужных респондентов
                    /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $player */
                    foreach ($whomToTell as $player) {

                        foreach ($this->onlineChars as $value) {
                            $obj = $this->onlineChars->current(); // current object
                            $assoc_key = $this->onlineChars->getInfo(); // return, if exists, associated with cur. obj. data; else NULL
                            $playerHash = $player->getHash();
                            $personalChannel = "personal." . $playerHash;
                            if ($assoc_key == $playerHash) {
                                $personalTopic = $this->topics[$personalChannel];
                                /** @var Topic $personalTopic */
                                $personalTopic->broadcast($thirdEcho);

                            }

//                            var_dump($obj);
//                            var_dump($assoc_key);
                        }

                    }
                }

                // Отправка результата клиенту
                $topic->broadcast($result);


//                var_dump($this->topics);

            }
        }

        // Если сообщение пришло в системный канал
        if ($channel == 'system.channel') {
            // Если передан хэш
            if (array_key_exists("HASH", $event)) {
                $hash = $event["HASH"];

                /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $char */
                $char = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);
                // Если передан валидный токен
                if ($char) {
                    // Соотнесение Websocket Client ID с симфони юзер-токеном
                    $this->onlineChars->attach($conn, $hash);
                } else {
                    $conn->close();
                }

                // Проверка хэша на уникальность
                if ($this->clients->hashIsUnique($hash)) {
                    // Если хэш уже присутствует
                    echo "Переподключение хэша: \033[0;33m", $hash, "\033[m\n";
                } else {
                    // Если хэш отсутствует
                    echo "Зарегистрирован новый хэш: \033[1;33m", $hash, "\033[m\n";

                    /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $char */
                    $char = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);
                    $char = $char[0];

                    // Добавление клиента в список юзеров онлайн
                    $this->clients->add($hash, $char);
                }
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function onReboot($incoming) {
        $data = json_decode($incoming, true);
        return false;
    }

    // Онлайн лист
    public function getOnlinelist() {
        $onlineList = array();
        foreach ($this->onlineChars as $key) {
            $onlineList[] = $this->onlineChars[$key];
        }

        return $onlineList;
    }
}