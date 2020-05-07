<?php

namespace Gekomod\SettingsBundle\Form;

use Gekomod\SettingsBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class SettingsType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
   public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('name', HiddenType::class,['required' => false])
        ->add('var', TextType::class, array(
            'required' => false,
        ))
    ;
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
        'data_class' => Settings::class,
    ));
}
    
    public function getName()
    {
        return 'settings';
    }
}
