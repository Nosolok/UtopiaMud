<?php

namespace Rottenwood\UtopiaMudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

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

    /**
     * Серверный ajax-приемник для клиентских команд
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxAction(Request $request) {
        $command = $request->request->get('chat');
        $result = $this->get('command')->execute($command);
        return new JsonResponse($result);
    }

    public function loginAction() {
        return $this->render('RottenwoodUtopiaMudBundle:Default:login.html.twig');
    }
}
