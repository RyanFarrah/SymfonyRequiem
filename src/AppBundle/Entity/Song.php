<?php

namespace AppBundle\Entity;

use AppBundle\Entity\Song;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\FormInterface;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Song
 *
 * @ORM\Table(name="song")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\SongRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Song
{

    const AUDIOFILEPATH = '/file/';
    const COVERFILEPATH = '/cover/';

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Nom du fichier audio
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez télécharger un fichier de musique", groups={"new"})
     * @Assert\File(
     *    mimeTypes={ "audio/mpeg" },
     *    mimeTypesMessage="Veuillez télécharger un fichier dans un format valide",
     *    maxSize = "10M",
     *    maxSizeMessage="Le fichier ne doit pas dépasser 10M",
     * groups={"new"}
     * )
     */
    private $audioFile;

    /**
     * Nom de la musique pour l'utilisateur
     *@var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank
    */
    private $audioName;

    /**
     * Nom du fichier de cover
     * @var string
     *
     * @ORM\Column(name="cover", nullable=true, type="string", length=255)
     * @Assert\File(
     *  mimeTypes={"image/jpeg", "image/png"},
     *  mimeTypesMessage="Veuillez télécharger un fichier dans un format valide",
     *  maxSize = "4M",
     *  maxSizeMessage="Le fichier ne doit pas dépasser 4M"
     * )
    */
    private $cover;

    /**
     * Utilisateur associé
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;
    
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
     * @param File|string $audioFile
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

    /**
     * Get the value of createdAt
     *
     * @return \DateTime
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param \DateTime  $createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return \DateTime
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param \DateTime  $updatedAt
     *
     * @return  self
     */ 
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get the value of user
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */ 
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get nom du fichier de cover
     */ 
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set nom du fichier de cover
     *
     * @return  self
     */ 
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Set DateTime columns when entity is created
     * @ORM\PrePersist
     */
    public function setDateValues()
    {
        $now = new \DateTime();
        $this->createdAt = $now;
        $this->updatedAt = $now;
    }

    /**
     * Set updatedAt property when entity is updated
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    public function transformAudioFileToModelData()
    {
        if(is_string($this->audioFile)) {
            return $this;
        } elseif($this->audioFile instanceof File) {
            $this->audioFile = $this->audioFile->getFileName();
            return $this;
        } else {
            throw new \Exception('Les données dans la propriété audioFileName ne peuvent pas être traitées');
        }
    }
}

