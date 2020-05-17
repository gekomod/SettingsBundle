<?php

namespace Gekomod\SettingsBundle\EventListener;

use Symfony\Component\DependencyInjection\Container;
use Gekomod\SettingsBundle\Entity\Settings;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class SeoListener
{
    /**
     * @var Container
     */
    private $container;
    
    /**
     * Constructor
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
            $this->container = $container;
    }
    
    public function getInfo($name) 
    {
        $em = $this->container->get('doctrine')->getManager();
        $myRepo = $em->getRepository(Settings::class);
        $op = $myRepo->findBy(["name" => $name]);
        return $op[0];
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $seoPage = $this->container->get('sonata.seo.page');

        if($this->getInfo('seo_title')->getVar() == null) {
            //NOT LOAD SEO
        } else {
            $seoPage->setTitle($this->getInfo('seo_title')->getVar());
            $seoPage->addMeta('property', 'og:title', $this->getInfo('seo_title')->getVar());
        }
        
        if($this->getInfo('seo_description')->getVar() == null) {
            //NOT LOAD SEO
        } else {
            $seoPage->addMeta('name', 'description', $this->getInfo('seo_description')->getVar());
            $seoPage->addMeta('property', 'og:type', 'CMS');
        }
    }
}

?>