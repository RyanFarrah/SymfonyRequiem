<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class LogoutHandler implements LogoutSuccessHandlerInterface
{

    /**
     * @var FlashBag
     */
    protected $flashBag;

    /**
     * @var Router
     */
    protected $router;

    public function __construct(FlashBag $flashBag, Router $router) {

        $this->flashBag = $flashBag;
        $this->router   = $router;
    }

    /**
     * Redirirge vers l'acceuil et affiche un @FlashBag
     *
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request){

        $this->flashBag->add(
            'success',
            'Vous vous êtes bien déconnecté'
        );

        return new RedirectResponse($this->router->generate("homepage"));

    }
}