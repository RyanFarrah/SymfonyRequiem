<?php

namespace AppBundle\Controller\Profile\Song;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Song;
use AppBundle\Form\Song\Type\EditSongType;
use AppBundle\Form\Song\Type\NewSongType;
use AppBundle\Form\Song\Handler\NewSongHandler;
use AppBundle\Service\File\Audio\FileAudioHandler;
use AppBundle\Form\Song\Handler\EditSongHandler;

class SongController extends Controller
{
    /**
     * @Route("/profile/edit/{id}", name="edit_song", requirements={"id"="\d+"})
     * @param Request
     * @param EntityManagerInterface
     * @param Song
     * @param FileAudioHandler
     */
    public function editAction(Request $request, EntityManagerInterface $em, Song $song, FileAudioHandler $fileAudioHandler)
    {
        $song->setAudioFile(
            new File($this->getParameter('audio_directory') . $song::AUDIOFILEPATH . $song->getAudioFile()));

        if($song->getCover()) {
            $song->setCover(
                new File($this->getParameter('audio_directory') . $song::COVERFILEPATH . $song->getCover()));
        }

        $handler = $this->get('hostnet.form_handler.factory')->create(EditSongHandler::class);

        if($handler->handle($request, $song)) {
            $this->addFlash(
                'notice',
                'Vous avez bien modifié votre musique'
            );
        }

        return $this->render('profile/song/edit_song.html.twig', array(
            'form' => $handler->getForm()->createView(),
        ));
    }

    /**
     * @Route("/profile/new", name="new_song")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param FileAudioHandler $fileAudioHandler
     * @return void
    */
    public function newAction(Request $request, EntityManagerInterface $em, FileAudioHandler $fileAudioHandler)
    {
        $repository = $this->getDoctrine()->getRepository(Song::class);

        $song = new Song();
        $form = $this->createForm(NewSongType::class, $song);
        //The clean form for not reinject the data when the form is valid
        $formClean = clone $form;
        
        $handler = $this->get('hostnet.form_handler.factory')->create(NewSongHandler::class);

        if($handler->handle($request, $song)) {
            $this->addFlash(
                'notice',
                'Vous avez bien enregistré votre musique'
            );
            $form = $formClean->createView();
        } else {
            $form = $handler->getForm()->createView();
        }

        return $this->render('profile/song/new_song.html.twig', array(
            'form' => $form
        ));
    }

    /**
     * @Route("/profile/remove/{id}", name="remove_song")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Song $song
     * @param FileAudioHandler $fileAudioHandler
     * @return void
    */
    public function removeAction(Request $request, EntityManagerInterface $em, Song $song, FileAudioHandler $fileAudioHandler)
    {
        $fileAudioHandler->removeSongEntityRelatedFiles($song);

        $em->remove($song);
        $em->flush();

        $this->addFlash(
            'notice',
            'Votre musique a bien été supprimée');

        return $this->redirectToRoute('profile');
    }
}