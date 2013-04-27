<?php

namespace Mattlab\MuseBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 */
class User
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var boolean
     */
    private $isAdmin;

    /**
     * @var string
     */
    private $keysWallet;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $protections;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->protections = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set isAdmin
     *
     * @param boolean $isAdmin
     * @return User
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin
     *
     * @return boolean
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Set keysWallet
     *
     * @param string $keysWallet
     * @return User
     */
    public function setKeysWallet($keysWallet)
    {
        $this->keysWallet = $keysWallet;

        return $this;
    }

    /**
     * Get keysWallet
     *
     * @return string
     */
    public function getKeysWallet()
    {
        return $this->keysWallet;
    }

    /**
     * Add protections
     *
     * @param \Mattlab\MuseBundle\Entity\Protection $protections
     * @return User
     */
    public function addProtection(\Mattlab\MuseBundle\Entity\Protection $protections)
    {
        $this->protections[] = $protections;

        return $this;
    }

    /**
     * Remove protections
     *
     * @param \Mattlab\MuseBundle\Entity\Protection $protections
     */
    public function removeProtection(\Mattlab\MuseBundle\Entity\Protection $protections)
    {
        $this->protections->removeElement($protections);
    }

    /**
     * Get protections
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProtections()
    {
        return $this->protections;
    }
}
