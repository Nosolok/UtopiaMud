<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 1:19
 */

namespace Rottenwood\UtopiaMudBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller {

    public function loginAction() {

        return $this->render('RottenwoodUtopiaMudBundle:Security:login.html.twig');
    }
}