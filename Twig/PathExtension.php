<?php

namespace Gekomod\SettingsBundle\Twig;

use Gekomod\SettingsBundle\Entity\Settings;
use Gekomod\SettingsBundle\Service\Settings_Get;
use Symfony\Component\Filesystem\Filesystem;
use Twig_SimpleFunction;
use Twig\Environment;

class PathExtension extends \Twig_Extension
{
    protected $doctrine;
    protected $container;
    protected $tts;
    protected $themedir;
    protected $filesystem;
    /** @var Settings */
    private $settings;
    // Retrieve doctrine from the constructor

    public function __construct($doctrine, $container, $tts, Settings_Get $setting)
    {        
        $this->doctrine = $doctrine;
        $this->container = $container;
        $this->settings = $setting;
        $this->themedir = $this->container->getParameter('kernel.project_dir');
        $this->filesystem = new Filesystem();
        $this->updatePath();
        $this->tts = $tts;
    }

    public function updatePath()
    {
        $dir = $this->themedir.'/templates/'.$this->settings->get('template', '');
        
        $this->checkDir($dir);
        
        new \Twig\Loader\FilesystemLoader($dir);
        return true;

    }

    public function checkDir($dir)
    {
        if (!$this->filesystem->exists($dir)) {
            $this->filesystem->mkdir($dir);
            $this->filesystem->chmod($dir, 0755, 0000, true);

            throw new \Exception('Folder Not Found - Created - Please Refresh Page');
        }
        return true;
    }

    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('file_get_contents', 'file_get_contents', ['is_safe' => ['html']]),
            new Twig_SimpleFunction('uniqid', 'uniqid'),
            new Twig_SimpleFunction('print_r', 'print_r'),
            new Twig_SimpleFunction('var_dump', 'var_dump'),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFunction('settings', [$this, 'getSettings']),
        ];
    }

    public function getSettings($name, $subname = null)
    {
        return $this->settings->get($name, $subname);
    }
}
