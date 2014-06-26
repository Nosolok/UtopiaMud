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

        $result = $this->get('command')->execute($command);

        $response = array(
            "command" => $result,
            //            "message" => $message,
        );
        return new JsonResponse($response);


    }
}
