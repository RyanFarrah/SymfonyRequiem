<?php
namespace AppBundle\Form\Song\Handler;

use AppBundle\Form\User\UserType;
use Hostnet\Component\FormHandler\HandlerConfigInterface;
use Hostnet\Component\FormHandler\HandlerTypeInterface;
use Hostnet\Component\FormHandler\ActionSubscriberInterface;
use Hostnet\Component\FormHandler\HandlerActions;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Form\General\Handler\HandlerActionsInterface;
use AppBundle\Form\Song\Type\NewSongType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use AppBundle\Service\File\Audio\FileAudioHandler;
use AppBundle\Form\Song\Type\EditSongType;
use AppBundle\Entity\Song;

final class EditSongHandler implements HandlerTypeInterface, ActionSubscriberInterface, HandlerActionsInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var FileAudioHandler
     */
    protected $fileAudioHandler;

    /**
     * @var TokenStorageInterface
     */
    protected $token;

    /**
     * @var Song
     */
    protected $songBeforeSubmit;

    public function __construct(EntityManagerInterface $em, FileAudioHandler $fileAudioHandler, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->fileAudioHandler = $fileAudioHandler;
        $this->token = $token;
    }

    /**
     * @inheritDoc
     */
    public function configure(HandlerConfigInterface $config)
    {
        $config->setType(EditSongType::class);
        $config->registerActionSubscriber($this);
        $config->setOptions(function(Song $song) {
            $this->songBeforeSubmit = clone $song;
            $this->songBeforeSubmit->transformAudioFileToModelData();
            return [];
        });
    }

     /**
     * @inheritDoc
     */
    public function getSubscribedActions()
    {
        return [
            HandlerActions::SUCCESS => 'onSuccess',
            HandlerActions::FAILURE => 'onFailure',
        ];
    }

    /**
     * Handle form's success
     */
    public function onSuccess($data, FormInterface $form, Request $request) 
    {
        if($data->getAudioFile()) {
            $this->fileAudioHandler->removeSongEntityRelatedFiles($this->songBeforeSubmit);
            $data->setCover(null);
            $audioFileName = $this->fileAudioHandler->newAudioFile($data);
            $data->setAudioFile($audioFileName);
        } else {
            $data->setAudioFile($this->songBeforeSubmit->getAudioFile());
        }  

        if($data->getCover()) {
            $coverFileName = $this->fileAudioHandler->newCoverFile($data);
        } elseif(isset($audioFileName)) {
            $coverFileName = $this->fileAudioHandler->getCoverFile($data, $audioFileName);
        } else {
            $coverFileName = null;
        }

        if($coverFileName) {
            if($coverFileName instanceof ConstraintViolationList) {
                $coverFileName = null;
            }
            $data->setCover($coverFileName);
        }

        $this->em->persist($data);
        $this->em->flush();

        return true;
    }

    /**
     * Handle form's failure
     */
    public function onFailure($data, FormInterface $form, Request $request) 
    {
        return false;
    }

}