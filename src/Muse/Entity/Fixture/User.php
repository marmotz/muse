<?php

namespace Muse\Entity\Fixture;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;

use Muse\Entity;

class User implements FixtureInterface {
    public function load(ObjectManager $manager) {
        $user = new Entity\User();
        $user->setName('Admin');
        $user->setEmail('admin@muse');
        $user->setPlainPassword('changeme');
        $user->setIsAdmin(true);

        $manager->persist($user);
        $manager->flush();
    }
}