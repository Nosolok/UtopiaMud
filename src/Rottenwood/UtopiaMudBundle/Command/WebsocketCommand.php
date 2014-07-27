<?php
/**
 * Author: Rottenwood
 * Date Created: 15.07.14
 */

namespace Rottenwood\UtopiaMudBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketCommand extends ContainerAwareCommand {

    protected $websocketServerProcess;

    protected function configure() {
        $this
            ->setName('mud:start')
            ->setDescription('Start Utopia MUD');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $loop   = \React\EventLoop\Factory::create();
        $pusher = $this->getContainer()->get('websocket');
        $worldService = $this->getContainer()->get('worldevent');
        $dataChannel = $this->getContainer()->get('datachannel');

        // Циклическое событие
        $loop->addPeriodicTimer(3, function() use ($pusher, $worldService) {
            $worldEventWeather = $worldService->weather();
            $worldEvent = array(
                "worldweather" => $worldEventWeather,
            );

            // Отправка всем сводки о погоде
            $pusher->sendToAll($worldEvent);
        });

        // Запуск вебсокет сервера
        $webSock = new \React\Socket\Server($loop);
        $webSock->listen(6661, '0.0.0.0'); // Привязка к 0.0.0.0 позволяет коннектиться удаленно
        new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer(
                    new \Ratchet\Wamp\WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();

    }
}