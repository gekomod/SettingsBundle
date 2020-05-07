<?php

namespace Gekomod\SettingsBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Admin\UrlGeneratorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Routing\Annotation\Route;

/**
  * Require ROLE_ADMIN for *every* controller method in this class.
  *
  * @IsGranted("ROLE_ADMIN")
  */
class SettingsAdmin extends AbstractAdmin
{
    
    protected $baseRoutePattern = '/gekomod/settings';
    protected $baseRouteName = 'admin_gekomod_settings';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list']);
        $collection->add('settings_cache','cache', [], [], [], '', ['HTTP'], ['GET']);
        $collection->add('settings_update','update', [], [], [], '', ['HTTP'], ['GET']);
        $collection->add('settings_save','save', [], [], [], '', ['HTTP'], ['POST']);
    }

}
