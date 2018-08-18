<?php

namespace AppBundle\Controller\Profile\Song;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Song;
use AppBundle\Form\SongType;

class EditSongController extends Controller
{
    /**
     * @Route("/profile/edit/{id}", name="edit_song", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, EntityManagerInterface $em)
    {
        $repository = $this->getDoctrine()->getRepository(Song::class);
        $audioDirectory = $this->container->getParameter('audio_directory') . '/';

        $song = new Song($audioDirectory);
        $audioDirectory = $song->getAudioDirectory();
        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $song->getAudioFile();

            $audioFile = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $audioName = $file->getClientOriginalName();

            $file->move(
                $this->getParameter('audio_directory'),
                $audioFile
            );

            $song->setAudioFile($audioFile);

            $song->setAudioName($audioName);

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