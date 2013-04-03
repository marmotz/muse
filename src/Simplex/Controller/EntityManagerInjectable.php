<?php

namespace Simplex\Controller;

use Doctrine\ORM\EntityManager;

interface EntityManagerInjectable {
    public function setEntityManager(EntityManager $em);
    public function getEntityManager();
}