<?php
namespace AppBundle\Form\User;

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

final class UserHandler implements HandlerTypeInterface, ActionSubscriberInterface
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function configure(HandlerConfigInterface $config)
    {
        $config->setType(UserType::class);
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
        $user = $data;
        $repository = $this->em->getRepository(User::class);
        $password = $this->passwordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);
        if(is_object($repository->findOneByUsername($user->getUsername()))) {
             return 'usernameExist';
        }
        $this->em->persist($user);
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