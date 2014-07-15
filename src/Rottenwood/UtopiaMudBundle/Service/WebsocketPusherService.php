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
    /**
     * A lookup of all the topics clients have subscribed to
     */
    private $subscribedTopics = array();

    public function __construct(Container $container) {
        $this->container = $container;
        // Создаю коллекцию подписчиков
        $this->clients = $this->container->get('datachannel');
    }


    public function onSubscribe(ConnectionInterface $conn, $topic) {
//        $this->subscribedTopics[$topic->getId()] = $topic;
//        echo "test subscribe\n";
////        var_dump($topic->getId());
//        $channel = substr($topic->getId(),9);
//        $user = $this->clients->getByHash($channel);
//
//        $this->clients->setChannels($channel, $topic);

//        var_dump($conn);
//        var_dump($topic);


    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }

    public function onOpen(ConnectionInterface $conn) {

    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
//        $conn->close();

        echo $topic->getId(), ": ", $event, "\n";
//        var_dump($event);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);

        $hash = $entryData["hash"];
        // Проверка хэша на уникальность
        if ($this->clients->hashIsUnique($hash)) {
            // Если хэш уже присутствует
            echo "Переподключение хэша: \033[0;33m", $hash, "\033[m\n";
        } else {
            // Если хэш отсутствует
            echo "Зарегистрирован новый хэш: \033[1;33m", $hash, "\033[m\n";

            $char = $this->container->get('doctrine.orm.entity_manager')->getRepository('RottenwoodUtopiaMudBundle:Player')
                ->getByHash($hash);

            // Добавление клиента в список подключенных клиентов
            $this->clients->add($hash, $char[0]);

            // Подключение к каналу пользователя
//            $channel = 'personal.' . $hash;
            $channel = $hash;

            //            // Подписка на персональный канал данных пользователя
            //            $session->subscribe($channel, $personalChannel);

            //            $topic = $this->subscribedTopics[$channel];
            //            $topic->broadcast($entryData);

//            echo "При подключении:\n";
//            var_dump($this->subscribedTopics);
//            var_dump($this);
//            echo "По хэшу:\n";
//            var_dump($this->subscribedTopics[$channel]);
        };

        //         если передана команда
        //        if (array_key_exists("CMD", $entryData)) {
        //
        //        }

//        var_dump($channel);

        $channel = $hash;

        var_dump($channel);
            var_dump($this->clients->channels);


        // If the lookup topic object isn't set there is no one to publish to
        if (!$this->clients->channelOnline($channel)) {
            echo "no channel\n";
            return;
        }
            echo "channel OK!\n";


        //        $topic = $this->subscribedTopics[$entryData['category']];
        $topic = $this->clients->getChannel($channel);

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);

    }


}