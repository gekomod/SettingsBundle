<?php

namespace Gekomod\SettingsBundle\Form;

use Gekomod\SettingsBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Gekomod\SettingsBundle\Form\SettingsType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class SettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(  'name', CollectionType::class, array(
                'entry_type' => SettingsType::class,
            ))
            ->add(  'var', CollectionType::class, array(
                'entry_type' => SettingsType::class
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Settings::class
        ]);
    }
}

?>