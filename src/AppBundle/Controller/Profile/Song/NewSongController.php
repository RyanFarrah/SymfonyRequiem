<?php

namespace AppBundle\Controller\Profile\Song;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use AppBundle\Entity\Song;
use AppBundle\Form\SongType;

class NewSongController extends Controller
{
    /**
     * @Route("/profile/new", name="new_song")
     */
    public function newAction(Request $request, EntityManagerInterface $em)
    {

        $repository = $this->getDoctrine()->getRepository(Song::class);

        $song = new Song();
        $form = $this->createForm(SongType::class, $song);
        //L'objet et le formulaire clean pour ne pas injecter les données transmis lors du soumission du formulaire
        $songClean = clone $song;
        $formClean = clone $form;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { 

            $song->setUser($this->getUser());

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
            $file = $song->getAudioFile();

            $audioFile = $this->generateUniqueFileName().'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('audio_directory'),
                $audioFile
            );

            $song->setAudioFile($audioFile);

            $song->setCreatedAt(new \DateTime());

            $song->setUpdatedAt(new \DateTime());

            $form->getData($song);

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

        $songs = $repository->findByUser($this->getUser()->getId());     

        return $this->render('profile/song/new_song.html.twig', array(
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