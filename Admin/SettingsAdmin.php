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

class SettingsAdmin extends AbstractAdmin
{

    protected function configureTabMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        $admin = $this->isChild() ? $this->getParent() : $this;

        $menu->addChild('Clear Cache', [
            'uri' => '/admin/gekomod/settings/settings/cache'
        ])->setAttribute('icon','fa fa-eraser');
        $menu->addChild('Check Updates', [
            'uri' => '/admin/gekomod/settings/settings/update'
        ])->setAttribute('icon','glyphicon glyphicon-save');
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array())
            ->add('var', null, array('required' => false))
            ->add('active', null, array())
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('id', null, array())
            ->add('name', null, array())
            ->add('var', null, array())
        ;
    }

    public function configureShowField(ShowMapper $showMapper){
        $showMapper
            ->add('id', null, array())
            ->add('name', null, array())
->add('var', null, array())
            ->add('active', null, array())
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
           ->addIdentifier('name', 'text')
            ->add('var', null, [
            'editable' => true
        ])
            ->add('id', null, array())
	    ->add('active','boolean', [
            'editable' => true
        ])
         ->add('_action', null, [
            'actions' => [
                'show' => [],
                'edit' => [],
                'delete' => [],
            ]
        ])
        ;
    }
}
