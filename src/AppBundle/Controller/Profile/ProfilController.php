<?php

namespace AppBundle\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Song;
use AppBundle\Form\SongType;

class ProfilController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function newAction(Request $request, EntityManagerInterface $em)
    {
        $repository = $this->getDoctrine()->getRepository(Song::class);

        $songs = $repository->findByUser($this->getUser()->getId());     

        return $this->render('profile/index.html.twig', array(
            'songs' => $songs,
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}