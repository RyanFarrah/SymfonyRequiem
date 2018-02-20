<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutHandler implements  LogoutSuccessHandlerInterface
{

    protected $template = null;

    protected $authorization;

    public function __construct(EngineInterface $template, AuthorizationCheckerInterface $authorization) {

        $this->authorization = $authorization;

        $this->template = $template;
    }

    public function onLogoutSuccess(Request $request){

        if (!$this->authorization->isGranted('ROLE_USER')) {
            throw new AccessDeniedException();
        }

        $content = $this->template->render("profile/logout.html.twig");

        return new Response($content);

    }
}