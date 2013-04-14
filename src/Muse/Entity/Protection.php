<?php

namespace Muse\Entity;

/**
 * @Entity(repositoryClass="Muse\Entity\Repository\Protection")
 */
class Protection {
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="text")
     */
    private $path;

    /**
     * @ManyToOne(targetEntity="User", inversedBy="protections")
     * @JoinColumn(name="protector_id", referencedColumnName="id")
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
     * @param \Muse\Entity\User $protector
     * @return Protection
     */
    public function setProtector(\Muse\Entity\User $protector = null)
    {
        $this->protector = $protector;

        return $this;
    }

    /**
     * Get protector
     *
     * @return \Muse\Entity\User
     */
    public function getProtector()
    {
        return $this->protector;
    }


    static public function getParentPaths($path) {
        $paths = array();

        while(!in_array($path, array('.', ''))) {
            $paths[] = $path;

            $path = dirname($path);
        }

        return $paths;
    }
}