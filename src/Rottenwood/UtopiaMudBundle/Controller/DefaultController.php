<?php

namespace Rottenwood\UtopiaMudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller {

    public function indexAction() {
        return $this->render('RottenwoodUtopiaMudBundle:Default:index.html.twig');
    }
}
