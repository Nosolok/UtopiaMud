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

        return $this->render('RottenwoodUtopiaMudBundle:Default:index.html.twig', $data);
    }
}
