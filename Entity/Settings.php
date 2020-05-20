<?php

namespace Gekomod\SettingsBundle\Entity;

use \Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Gekomod\SettingsBundle\Repository\SettingsRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Settings
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $var;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $active;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Version
     */
    protected $version;
    
    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getVar(): ?string
    {
        return $this->var;
    }

    public function setVar(string $var): self
    {
        $this->var = $var;

        return $this;
    }

    public function getActive(): ?string
    {
        return $this->active;
    }

    public function setActive(string $active): self
    {
        $this->active= $active;

        return $this;
    }

    public function addTag(Settings $name)
    {
        $this->name->add($name);
    }
}
