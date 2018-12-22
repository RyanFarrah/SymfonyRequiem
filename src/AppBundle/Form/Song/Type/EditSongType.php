<?php

namespace AppBundle\Form\Song\Type;

use AppBundle\Entity\Song;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Validation\ValidationGroupResolver;
use Symfony\Component\HttpFoundation\File\File;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;


class EditSongType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('audioName', TextType::class, array('label' => 'Choisissez son nom'))
            ->add('audioFile', FileType::class, array(
                'label' => 'Changer de fichier audio',
                'required' => false))
            ->add('save', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Song::class,
            'validation_groups' => function (FormInterface $form) {
                $data = $form->getData();

                if ($data->getAudioFile()) {
                    return array('Default', 'new');
                } else {
                    return array('Default');
                }
            }
        ));
    }
}