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
use Symfony\Component\Validator\ConstraintViolationList;

final class NewSongHandler implements HandlerTypeInterface, ActionSubscriberInterface, HandlerActionsInterface
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
        $config->setType(NewSongType::class);
        $config->registerActionSubscriber($this);
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
        $audioFileName = $this->fileAudioHandler->newAudioFile($data);
        if($data->getCover()) {
            $coverFileName = $this->fileAudioHandler->newCoverFile($data);
        } else {
            $coverFileName = $this->fileAudioHandler->getCoverFile($data, $audioFileName);
        }

        $now = new \DateTime();

        $data->setAudioFile($audioFileName);
        $data->setUser($this->token->getToken()->getUser());
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