<?php

namespace Gekomod\SettingsBundle\EventListener;

use Gekomod\SettingsBundle\Entity\Settings;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class DebugListener
{
    /**
     * @var Container
     */
    private $container;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $myRepo = $em->getRepository(Settings::class);
        $op = $myRepo->findBy(['name' => 'debug']);
        if ($op[0]->getVar() == 'false') {
            $this->container->get('profiler')->disable();
        }
        /*$option = $this->container->get('settings.new')->getSettings('debug');*/
    }
}
