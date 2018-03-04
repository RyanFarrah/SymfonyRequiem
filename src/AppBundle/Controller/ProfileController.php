<?php

namespace AppBundle\Controller;

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

        ?>

        <audio src="<?=$audioDirectory?>/4dd3d93db267c83c15261373583ced1b.mp3">
        </audio>
        <!-- 
         <audio src="/var/www/html/symfony3/web/uploads/audio/d9cb023cd4d566f695c72ea614d39212.mp3" autoplay >
         </audio>  -->
         
         <?php
        

        return $this->render('profile/index.html.twig', array(
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