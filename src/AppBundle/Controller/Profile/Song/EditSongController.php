<?php

namespace AppBundle\Controller\Profile\Song;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

use AppBundle\Entity\Song;
use AppBundle\Form\SongType;

class EditSongController extends Controller
{
    /**
     * @Route("/profile/edit/{id}", name="edit_song", requirements={"id"="\d+"})
     */
    public function editAction(Request $request, EntityManagerInterface $em, Song $song)
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
                /** @var Symfony\Component\HttpFoundation\File\UploadedFile $audioFile */
                $audioFile = $song->getAudioFile();
                $audioNameFile = $this->generateUniqueFileName().'.'.$audioFile->guessExtension();
                $audioFile->move(
                    $audioPath . $song::AUDIOFILEPATH,
                    $audioNameFile
                );
            }

            $song->setAudioFile($audioNameFile);

            $song->setUpdatedAt(new \DateTime());

            $em->persist($song);

            $em->flush();

        }

        return $this->render('profile/song/edit_song.html.twig', array(
            'form' => $form->createView(),
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