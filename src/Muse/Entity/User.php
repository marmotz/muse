<?php

namespace Muse\Entity;

/**
 * @Entity(repositoryClass="Muse\Entity\Repository\User")
 */
class User {
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string", length=32)
     */
    private $name;

    /**
     * @Column(type="string", length=64)
     */
    private $email;

    /**
     * @Column(type="string", length=40)
     */
    private $password;

    /**
     * @Column(type="string", length=32)
     */
    private $salt;

    /**
     * @Column(type="boolean")
     */
    private $isAdmin;


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
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        if($this->salt === null) {
            $this->salt = '';

            for($i = 0; $i < 32; $i++) {
                $this->salt .= chr(rand(33, 126));
            }

            var_dump($this->salt);
        }

        return $this->salt;
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


    public function cryptPassword($password) {
        return sha1($password . $this->getSalt());
    }


    public function isPasswordValid($password) {
        return $this->getPassword() === $this->cryptPassword($password);
    }

    public function setPlainPassword($password) {
        return $this->setPassword(
            $this->cryptPassword($password)
        );
    }
}