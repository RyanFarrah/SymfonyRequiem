<?php

namespace AppBundle\Controller\Security;

use AppBundle\Form\User\UserType;
use AppBundle\Entity\User;
use AppBundle\Form\User\UserHandler;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();
        $usernameExist = null;
        $repository = $this->getDoctrine()->getRepository(User::class);
        $handler = $this->get('hostnet.form_handler.factory')->create(UserHandler::class);

        $response = $handler->handle($request, $user);
        if($response === true) {
            return $this->redirectToRoute('profile');
        } elseif ($response === 'usernameExist') {
            $usernameExist = true;
        }

        return $this->render(
            'profile/register.html.twig',
            array('form' => $handler->getForm()->createView(),
                  'usernameExist' => $usernameExist)
        );
    }
}