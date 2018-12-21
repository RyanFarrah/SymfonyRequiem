<?php
namespace AppBundle\Form\General\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;


interface HandlerActionsInterface 
{
    public function onSuccess($data, FormInterface $formInterface, Request $request);

    public function onFailure($data, FormInterface $formInterface, Request $request);
}