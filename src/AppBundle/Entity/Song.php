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

    public function __construct($audioDirectory) {
        
        $this->setAudioDirectory($audioDirectory);
    }

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
     * @ORM\Column(name="AudioFile", type="string", length=255)
     * @Assert\NotBlank(message="Please, upload a audio file")
     * @Assert\File(
     *    mimeTypes={ "audio/mpeg" },
     *    maxSize = "30M")
     */
    private $audioFile;

    /**
     *@var string
     *
     *@ORM\Column(name="AudioName", type="string", length=255)
     *@Assert\NotBlank()
    */

    private $audioName;

    private $audioDirectory;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setAudioDirectory($audioDirectory) {

        $audioDirectory = str_replace($_SERVER['DOCUMENT_ROOT'], '', $audioDirectory);
        
        $this->audioDirectory = $audioDirectory;
    }

    public function getAudioDirectory() 
    {
        return $this->audioDirectory;
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
         if(isset($this->audioFile)) {
            $this->audioFile = $this->audioDirectory . $audioFile;
         }
         else {
            $this->audioFile = $audioFile;
         }
 
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

