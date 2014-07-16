<?php
/**
 * Author: Rottenwood
 * Date Created: 15.07.14
 */

namespace Rottenwood\UtopiaMudBundle\Command;

use Rottenwood\UtopiaMudBundle\Entity;
use Rottenwood\UtopiaMudBundle\Service\WebsocketPusherService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class WebsocketCommand extends ContainerAwareCommand {

    protected $websocketServerProcess;

    protected function configure() {
        $this
            ->setName('mud:websocket')
            ->setDescription('Start WebSocket server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $loop   = \React\EventLoop\Factory::create();
        $pusher = $this->getContainer()->get('websocket');

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new \React\ZMQ\Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'onReboot'));

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new \React\Socket\Server($loop);
        $webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new \Ratchet\Server\IoServer(
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