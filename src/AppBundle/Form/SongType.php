<?php

namespace AppBundle\Form;

use AppBundle\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class SongType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('audioName', TextType::class, array('label' => 'Choisissez son nom'))
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
                if($event->getData()->getAudioFile() === null) {
                    $event->getForm()->add('audioFile', FileType::class, array('label' => 'Choisissez un fichier audio'));
                }
                else {
                    return;
                }
            })
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Song::class,
        ));
    }
}