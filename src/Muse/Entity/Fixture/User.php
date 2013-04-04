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
        $user->setPassword('9fe65a1a4df7f0b2fef3c8493db4dbd0478300f0');
        $user->setSalt('Paiv6IenahS5iedeiPhi2OLeiFaiHaek');
        $user->setIsAdmin(true);

        $manager->persist($user);
        $manager->flush();
    }
}