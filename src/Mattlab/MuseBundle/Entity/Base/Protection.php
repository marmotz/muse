<?php

namespace Mattlab\MuseBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Protection
 */
class Protection
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \Mattlab\MuseBundle\Entity\User
     */
    private $protector;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Protection
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set protector
     *
     * @param \Mattlab\MuseBundle\Entity\User $protector
     * @return Protection
     */
    public function setProtector(\Mattlab\MuseBundle\Entity\User $protector = null)
    {
        $this->protector = $protector;

        return $this;
    }

    /**
     * Get protector
     *
     * @return \Mattlab\MuseBundle\Entity\User
     */
    public function getProtector()
    {
        return $this->protector;
    }
}
