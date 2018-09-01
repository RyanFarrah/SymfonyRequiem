<?php

namespace AppBundle\Controller\Profile;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Song;
use AppBundle\Form\SongType;

class ProfileController extends Controller
{
    /**
     * @Route("/profile", name="profile")
     */
    public function newAction(Request $request, EntityManagerInterface $em)
    {
        $repository = $this->getDoctrine()->getRepository(Song::class);

        $song = new Song($audioDirectory);
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $song->getAudioFile();

            $audioFile = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('audio_directory'),
                $audioFile
            );

            $song->setAudioFile($audioFile);

            $em->persist($song);

            $em->flush();

        }

        $songs = $repository->findAll();     

        return $this->render('profile/index.html.twig', array(
            'songs' => $songs,
            'form' => $form->createView()
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