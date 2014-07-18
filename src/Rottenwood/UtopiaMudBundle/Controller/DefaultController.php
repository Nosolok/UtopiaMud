<?php

namespace Rottenwood\UtopiaMudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    /**
     * Вызов главной страницы. Запись токена игрока в базу данных
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction() {
        $session = $this->get('session')->getId();
        $id = $this->get('security.context')->getToken()->getUser();

        // Запись токена игрока в базу
        $this->get('doctrine.orm.entity_manager')->getRepository('RottenwoodUtopiaMudBundle:Player')->saveHash($id,
            $session);

        $data = array();
        $data['hash'] = $session;
        $data['serverip'] = $_SERVER["SERVER_ADDR"];

        return $this->render('RottenwoodUtopiaMudBundle:Default:index.html.twig', $data);
    }

    public function testAction() {
        $session = $this->get('session')->getId();

        $entryData = array(
            'hash' => $session,
            'CMD'    => "look",
            'article'  => "kittensCategory",
            'when'     => time(),
        );

        // This is our new stuff
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $socket->send(json_encode($entryData));

        $data = array();
        $data['hash'] = $session;

        return $this->render('RottenwoodUtopiaMudBundle:Default:test.html.twig', $data);
    }
}
