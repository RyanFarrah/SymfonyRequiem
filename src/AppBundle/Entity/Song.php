<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(name="AudioFile", type="string", length=255)
     * @Assert\NotBlank(message="Please, upload a audio file")
     * @Assert\File(
     *    mimeTypes={ "audio/mpeg" },
     *    maxSize = "30M")
     */
    private $audioFile;


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
}

