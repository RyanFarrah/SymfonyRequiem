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
use AppBundle\Service\FileHandler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class NewSongHandler implements HandlerTypeInterface, ActionSubscriberInterface, HandlerActionsInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var FileHandler
     */
    protected $fileHandler;

    /**
     * @var TokenStorageInterface
     */
    protected $token;

    public function __construct(EntityManagerInterface $em, FileHandler $fileHandler, TokenStorageInterface $token)
    {
        $this->em = $em;
        $this->fileHandler = $fileHandler;
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
        $audioFileName = $this->fileHandler->newAudioFile($data);
        $coverFileName = $this->fileHandler->getCoverFile($data, $audioFileName);

        $now = new \DateTime();

        $data->setAudioFile($audioFileName);
        $data->setUser($this->token->getToken()->getUser());
        if($coverFileName) {
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