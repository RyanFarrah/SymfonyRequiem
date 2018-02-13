<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends Controller
{
    /**
    * @Route("/profile", name="homepage")
    */
    public function indexAction()
    {

        return $this->render('default/index.html.twig');
    }
}