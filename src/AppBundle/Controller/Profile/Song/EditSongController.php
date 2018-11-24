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

class EditSongController extends Controller
{
    /**
     * @Route("/profile/edit/{id}", name="edit_song", requirements={"id"="\d+"})
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
}