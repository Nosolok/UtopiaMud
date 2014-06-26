<?php

namespace Rottenwood\UtopiaMudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('RottenwoodUtopiaMudBundle:Default:index.html.twig');
    }

    public function ajaxAction(Request $request) {
        $command = $request->request->get('chat');

        /**
         * Проверка существования команды
         */
        $serviceMethods = get_class_methods($this->get('command'));

        if (!in_array($command, $serviceMethods)) {
            $message = "0:1";
        } else {
            $message = $this->get('command')->$command();
        }

        $response = array(
//            "command" => $result,
            "message" => $message,
        );
        return new JsonResponse($response);


    }
}
