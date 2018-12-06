<?php

namespace AppBundle\Controller\Profile\Song;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Service\FileHandler;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Song;
use AppBundle\Form\Song\SongType;

class SongController extends Controller
{
    /**
     * @Route("/profile/edit/{id}", name="edit_song", requirements={"id"="\d+"})
     * @param Request
     * @param EntityManagerInterface
     * @param Song
     * @param FileHandler
     */
    public function editAction(Request $request, EntityManagerInterface $em, Song $song, FileHandler $fileHandler)
    {

        $audioNameFile = $song->getAudioFile();

        $audioPath = $this->getParameter('audio_directory');

        $song->setAudioFile(
            new File($this->getParameter('audio_directory') . $song::AUDIOFILEPATH . $song->getAudioFile()));

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $song = $form->getData();

            if($song->getAudioFile() !== null) {
                unlink($audioPath . $song::AUDIOFILEPATH . $audioNameFile);
                $audioNameFile = $fileHandler->newAudioFile($song);
            }

            $song->setAudioFile($audioNameFile);

            $em->persist($song);
            $em->flush();

        }

        return $this->render('profile/song/edit_song.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/profile/new", name="new_song")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FileHandler $fileHandler
     * @return void
    */
    public function newAction(Request $request, EntityManagerInterface $em, FileHandler $fileHandler)
    {

        $repository = $this->getDoctrine()->getRepository(Song::class);

        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        //L'objet et le formulaire clean pour ne pas injecter les données transmis lors du soumission du formulaire
        $songClean = clone $song;
        $formClean = clone $form;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $song = $form->getData();

            $audioFileName = $fileHandler->newAudioFile($song);
            $coverFileName = $fileHandler->getCoverFile($song, $audioFileName);

            $now = new \DateTime();

            $song->setAudioFile($audioFileName);
            $song->setUser($this->getUser());
            if($coverFileName) {
                $song->setCover($coverFileName);
            }
            
            $em->persist($song);
            $em->flush();   

            $audioName = $song->getAudioName();

            $this->addFlash(
                'notice',
                "Vous avez bien enregistré votre musique : $audioName"
            );
            
            $song = $songClean;
            $form = $formClean;

        } 

        return $this->render('profile/song/new_song.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}