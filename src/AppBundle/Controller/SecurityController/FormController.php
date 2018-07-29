<?php

namespace AppBundle\Controller\SecurityController;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class FormController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {

        $user = new User();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $userUsername = $user->getUsername();

            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            if(is_object($repository->findOneByUsername($userUsername))) {
                return $this->render(
                    'profile/register.html.twig',
                    array('form' => $form->createView(),
                        'usernameExist' => true)
                );
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            

            return $this->redirectToRoute('profile');

        }

        return $this->render(
            'profile/register.html.twig',
            array('form' => $form->createView())
        );
    }
}