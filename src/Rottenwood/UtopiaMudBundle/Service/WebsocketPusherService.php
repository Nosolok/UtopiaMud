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
    /**
     * A lookup of all the topics clients have subscribed to
     */
    private $subscribedTopics = array();

    public function __construct(Container $container) {
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        // Создаю коллекцию подписчиков
        $this->clients = $this->container->get('datachannel');

        // Перезагрузка клиента
        $this->sendData();
    }

    public function sendData() {
        $data = array(
//            'hash' => $session,
//            'CMD'    => "look",
            'article'  => "kittensCategory",
//            'when'     => time(),
        );

        // This is our new stuff
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode($data));
    }


    public function onSubscribe(ConnectionInterface $conn, $topic) {
        //        $this->subscribedTopics[$topic->getId()] = $topic;
        $channel = $topic->getId();
        echo "Подписка на $channel\n";
        ////        var_dump($topic->getId());
        //        $channel = substr($topic->getId(),9);
        //        $user = $this->clients->getByHash($channel);
        //
        //        $this->clients->setChannels($channel, $topic);

        //        var_dump($conn);
        //        var_dump($topic);

        // Проверка

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

        $channel = $topic->getId();

        // Если подписка оформлена на персональный канал
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

                var_dump($result);
            }
        }




//        echo $channel, ": ", $event, "\n";
//        print_r($event);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function onReboot($incoming) {
        $data = json_decode($incoming, true);

        var_dump($data);
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