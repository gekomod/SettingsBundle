<?php

namespace Gekomod\SettingsBundle\Twig;

use Gekomod\SettingsBundle\Entity\Settings;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_Environment;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Gekomod\SettingsBundle\Service\Settings_Get;

class PathExtension extends \Twig_Extension
{
    protected $doctrine;
    protected $container;
    protected $tt;
    protected $themedir;
    protected $filesystem;
    /** @var Settings */
    private $settings;
    // Retrieve doctrine from the constructor

    public function __construct($doctrine,$container,$tt,Settings_Get $setting)
    {
        $this->doctrine = $doctrine;
	$this->container = $container;
	$this->tt = $tt;
        $this->filesystem = new Filesystem();
        $this->settings = $setting;
        
	$this->themedir = $this->container->getParameter('kernel.project_dir');
	$this->updatePath();
    }

    public function updatePath(){
        $dir = $this->themedir.'/templates/'.$this->settings->get('template', '');
        $this->checkDir($dir);
        $loader = new \Twig\Loader\FilesystemLoader($dir);
        return true;
    }
 
    public function checkDir($dir) {
        if(!$this->filesystem->exists($dir)) {
            $this->filesystem->mkdir($dir);
            $this->filesystem->chmod($dir, 0755, 0000, true);
            throw new \Exception('Folder Not Found - Created - Please Refresh Page');
        }
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFunction('settings', array($this, 'getSettings')),
        );
    }
    
    public function getSettings($name, $subname = null)
    {
        return $this->settings->get($name, $subname);
    }

}