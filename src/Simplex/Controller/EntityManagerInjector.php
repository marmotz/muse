<?php

namespace Simplex\Controller;

use Doctrine\ORM\EntityManager;

trait EntityManagerInjector {
    protected $em;


    public function setEntityManager(EntityManager $em) {
        $this->em = $em;
    }

    public function getEntityManager() {
        return $this->em;
    }
}