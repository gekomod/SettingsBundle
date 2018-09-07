<?php

namespace Gekomod\SettingsBundle\Service;

use Gekomod\SettingsBundle\Entity\Settings;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;

class Settings_Get
{
    public $doctrine;

    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getSettings($options)
    {
        $em = $this->doctrine->getManager();
        $myRepo = $em->getRepository(Settings::class);
        $op = $myRepo->findBy(["name" => $options]);
        return $op[0]->getVar();
    }
}

?>