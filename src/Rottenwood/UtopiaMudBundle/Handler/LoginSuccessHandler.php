<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 17:19
 */

namespace Rottenwood\UtopiaMudBundle\Handler;

use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface {

    protected $router;
    protected $security;

    public function __construct(Router $router, SecurityContext $security) {
        $this->router = $router;
        $this->security = $security;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('rottenwood_utopiamud_index'));
        } elseif ($this->security->isGranted('ROLE_ADMIN')) {
            $response = new RedirectResponse($this->router->generate('rottenwood_utopiamud_index'));
        } elseif ($this->security->isGranted('ROLE_USER')) {

            $response = new RedirectResponse($this->router->generate('rottenwood_utopiamud_index'));
        } else {
            $response = new RedirectResponse($this->router->generate('rottenwood_utopiamud_index'));
        }

        return $response;
    }

}
