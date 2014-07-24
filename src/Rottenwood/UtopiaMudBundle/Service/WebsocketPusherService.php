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

        // Команды выполняемые при загрузке сервера
        $startCommands = $container->get('commandsystem')->import();

        foreach ($startCommands as $startComandResult) {
            echo "$startComandResult\n";
        }
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if (is_object($topic)) {
            $channel = $topic->getId();
        } else {
            $channel = $topic;
        }

        echo "Подписка на $channel\n";

        // Запись канала в список
        $this->topics[$channel] = $topic;

    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->onlineChars->attach($conn);

        echo "Новое соединение!\n";
    }

    public function onClose(ConnectionInterface $conn) {
        // Удаление вышедшего клиента из списка юзеров
        $whoQuits = $this->onlineChars->offsetGet($conn);

        $hash = $whoQuits;
        /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $char */
        $char = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);
        $char = $char[0];

        // Сообщение всем о дисконнекте
        $message = array();
        $message["message"] = "0:6:2";
        $message["who"] = $char->getUsername();
        $this->sendToAll($message);

        $this->clients->remove($whoQuits);
        $this->onlineChars->detach($conn);
        echo "Соединение разорвано.\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        if (is_object($topic)) {
            $channel = $topic->getId();
        } else {
            $channel = $topic;
        }

        // Если сообщение пришло в персональный канал
        if ((substr($channel, 0, 9) == 'personal.')) {
            $this->onPublishPersonal($channel, $event, $topic);
        }

        // Если сообщение пришло в системный канал
        if ($channel == 'system.channel') {
            $this->onPublishSystem($conn, $event);
        }
    }

    // Публикация в персональный канал
    public function onPublishPersonal($channel, $event, Topic $topic) {

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
                        $this->onlineChars->current(); // current object
                        $assoc_key = $this->onlineChars->getInfo(); // return, if exists, associated with cur. obj. data; else NULL
                        $playerHash = $player->getHash();
                        $personalChannel = "personal." . $playerHash;
                        if ($assoc_key == $playerHash) {
                            $personalTopic = $this->topics[$personalChannel];
                            /** @var Topic $personalTopic */
                            $personalTopic->broadcast($thirdEcho);

                        }
                    }
                }
            }

            if (array_key_exists("4rd", $result)) {
                // Список респондентов
                $whomToTell = $result["4rd"];

                // Сообщение для респондентов
                $thirdEcho = $result["4rdecho"];
                unset($result["4rd"]);
                unset($result["4rdecho"]);

                // Поиск каналов данных нужных респондентов
                /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $player */
                foreach ($whomToTell as $player) {

                    foreach ($this->onlineChars as $value) {
                        $this->onlineChars->current(); // current object
                        $assoc_key = $this->onlineChars->getInfo(); // return, if exists, associated with cur. obj. data; else NULL
                        $playerHash = $player->getHash();
                        $personalChannel = "personal." . $playerHash;
                        if ($assoc_key == $playerHash) {
                            $personalTopic = $this->topics[$personalChannel];
                            /** @var Topic $personalTopic */
                            $personalTopic->broadcast($thirdEcho);

                        }
                    }
                }
            }

            // Отправка результата клиенту
            if (is_object($topic)) {
                $topic->broadcast($result);
            } else {
                echo "$topic - не объект.";
            }
        }
    }

    public function onPublishSystem(ConnectionInterface $conn, $event) {
        // Если передан хэш
        if (array_key_exists("HASH", $event)) {
            $hash = $event["HASH"];

            /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $char */
            $char = $this->em->getRepository('RottenwoodUtopiaMudBundle:Player')->getByHash($hash);
            // Если передан невалидный токен
            if (!$char) {
                $conn->close();
                return;
            }

            // Соотнесение Websocket Client ID с симфони юзер-токеном
            $this->onlineChars->attach($conn, $hash);


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

                // Сообщение всем о новом коннекте
                $message = array();
                $message["message"] = "0:6:1";
                $message["who"] = $char->getUsername();
                $this->sendToAll($message);
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    // Онлайн лист
    public function getOnlinelist() {
        $onlineList = array();
        foreach ($this->onlineChars as $key) {
            $onlineList[] = $this->onlineChars[$key];
        }

        return $onlineList;
    }

    /**
     * Отправка сообщений в нужные дата-каналы
     * @param array $playersToMessage Players
     * @param       $broadcast
     */
    public function sendToList($playersToMessage, $broadcast) {
        // Поиск каналов данных нужных респондентов
        /** @var \Rottenwood\UtopiaMudBundle\Entity\Player $player */
        foreach ($playersToMessage as $player) {

            foreach ($this->onlineChars as $value) {
                $this->onlineChars->current(); // current object
                $assoc_key = $this->onlineChars->getInfo(); // return, if exists, associated with cur. obj. data; else NULL
                $playerHash = $player->getHash();
                $personalChannel = "personal." . $playerHash;
                if ($assoc_key == $playerHash) {
                    $personalTopic = $this->topics[$personalChannel];
                    /** @var Topic $personalTopic */
                    $personalTopic->broadcast($broadcast);

                }
            }
        }
    }

    public function sendToAll($broadcast) {
        foreach ($this->onlineChars as $value) {
            $hash = $this->onlineChars->getInfo(); // return, if exists, associated with cur. obj. data; else NULL
            $personalChannel = "personal." . $hash;
            $personalTopic = $this->topics[$personalChannel];
            /** @var Topic $personalTopic */
            $personalTopic->broadcast($broadcast);
        }
    }

}