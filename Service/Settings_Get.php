<?php

namespace Gekomod\SettingsBundle\Service;

use Gekomod\SettingsBundle\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManager;

class Settings_Get
{
    /** @var EntityManager */
    private $ems;
    
    /** @var SettingsRepository */
    private $repository;

    private $settings = array();
    
    public function __construct(EntityManager $ems)
    {
        $this->ems = $ems;
        $this->repository = $ems->getRepository('Gekomod\SettingsBundle\Entity\Settings');
    }

    private function fetch($name)
    {
        $setting = $this->repository->findOneBy(array('name' => $name));
        if ($setting) {
            return $setting->getVar();
        }

        return null;
    }
    
    private function load($name)
    {
            return $this->fetch($name);
    }
    
    /**
     * Get one setting
     *
     * @param string $name Setting name or group name (if $subname is set)
     * @param mixed|null $default The default value if the setting key does not exist
     * @return mixed
     */
    public function get($name, $default = null)
    {
            if (!isset($this->settings[$name])) {
                $this->settings[$name] = $this->load($name);
            }
            return $this->settings[$name] ?: $default;
    }
}

?>
