<?php

namespace Gekomod\SettingsBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Route\RouteCollection;

class SettingsopAdmin extends AbstractAdmin {
    protected $baseRoutePattern = '/gekomod/settings/settings';
    protected $baseRouteName = 'admin_gekomod_settings';

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['cache']);
        $collection->remove('list');
        $collection->add('settings_cache','cache', [], [], [], '', ['https'], ['GET']);
    }
}