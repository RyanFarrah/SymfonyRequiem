<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Song;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\Bundle\DoctrineBundle\Registry;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SongRepository")
 */
class Song
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="audio_file", type="string", length=255)
     * @Assert\NotBlank(message="Please, upload a audio file")
     * @Assert\File(
     *    mimeTypes={ "audio/mpeg" },
     *    maxSize = "30M")
     */
    private $audioFile;

    /**
     *@var string
     *
     *@ORM\Column(name="audio_name", type="string", length=255)
    */
    private $audioName;

    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * Set audioFile
     *
     * @param string $audioFile
     *
     * @return Song
     */
     public function setAudioFile($audioFile)
     {
        $this->audioFile = $audioFile;
 
         return $this;
     }
 
     /**
      * Get audioFile
      *
      * @return string
      */
     public function getAudioFile()
     {
         return $this->audioFile;
     }

     public function setAudioName($audioName) {

        $this->audioName = $audioName;

        return $this;

     }

     public function getAudioName() {
        
        return $this->audioName;;
     }

}

