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
     * @var integer
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

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

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
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
    
    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version= $version;

        return $this;
    }

    public function addTag(Settings $name)
    {
        $this->name->add($name);
    }
    
}
