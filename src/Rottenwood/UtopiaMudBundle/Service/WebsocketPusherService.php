<?php
/**
 * Author: Rottenwood
 * Date Created: 16.07.14 1:21
 */

namespace Rottenwood\UtopiaMudBundle\Service;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\DependencyInjection\Container;

class WebsocketPusherService implements WampServerInterface {

    private $container;
    private $clients;
    private $em;
    private $onlineChars;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        // Создаю коллекцию подписчиков
        $this->clients = $this->container->get('datachannel');
        $this->onlineChars = new \SplObjectStorage;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        $channel = $topic->getId();
        echo "Подписка на $channel\n";
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->onlineChars->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // Удаление вышедшего клиента из списка юзеров
        $whoQuits = $this->onlineChars->offsetGet($conn);
        $this->clients->remove($whoQuits);
        $this->onlineChars->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
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
                $command = $event["CMD"];
                $result = $this->container->get('command')->execute($command, $char);

                // Отправка результата клиенту
                $topic->broadcast($result);
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