<?php

namespace Gekomod\SettingsBundle\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class SettingsAdmin extends AbstractAdmin
{
    protected $baseRoutePattern = '/gekomod/settings';
    protected $baseRouteName = 'admin_gekomod_settings';

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->clearExcept(['list', 'create', 'delete']);
        $collection->add('settings_cache', 'cache', [], [], [], '', ['HTTP'], ['GET']);
        $collection->add('settings_update', 'update', [], [], [], '', ['HTTP'], ['GET']);
        $collection->add('settings_save', 'save', [], [], [], '', ['HTTP'], ['POST']);
        $collection->add('settings_apcu_cache', 'cacheapcu', [], [], [], '', ['HTTP'], ['GET']);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('name')
            ->add('var')
            ->add('active', ChoiceType::class, ['choices' => ['Active' => 'true', 'Not Active' => 'false']])
            ->add('version', IntegerType::class);
    }
}
