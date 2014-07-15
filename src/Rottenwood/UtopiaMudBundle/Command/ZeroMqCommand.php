<?php
/**
 * Author: Rottenwood
 * Date Created: 15.07.14
 */

namespace Rottenwood\UtopiaMudBundle\Command;

use Rottenwood\UtopiaMudBundle\Entity;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ZeroMqCommand extends ContainerAwareCommand {

    protected $websocketServerProcess;

    protected function configure() {
        $this
            ->setName('mud:zero')
            ->setDescription('Start ZeroMQ server');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        //  Prepare our context and subscriber
        $context = new \ZMQContext();
        $subscriber = new \ZMQSocket($context, \ZMQ::SOCKET_SUB);
        $subscriber->connect("tcp://localhost:5563");
        $subscriber->setSockOpt(\ZMQ::SOCKOPT_SUBSCRIBE, "system.channel");

        while (true) {
            //  Read envelope with address
            $address = $subscriber->recv();
            //  Read message contents
            $contents = $subscriber->recv();
            printf ("[%s] %s%s", $address, $contents, PHP_EOL);
        }
    }
}