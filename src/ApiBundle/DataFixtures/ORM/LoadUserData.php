<?php

namespace ApiBundle\DataFixtures\ORM;

use ApiBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin');
        $userAdmin->setEmail('admin@admin.com');
        $userAdmin->setPlainPassword('admin123');
        $userAdmin->setEnabled(true);
        $userAdmin->setRoles(array('ROLE_ADMIN'));

        $plainUser = new User();
        $plainUser->setUsername('user');
        $plainUser->setEmail('user@user.com');
        $plainUser->setPlainPassword('user123');
        $plainUser->setEnabled(true);
        $plainUser->setRoles(array('ROLE_USER'));

        $manager->persist($userAdmin);
        $manager->persist($plainUser);
        $manager->flush();
    }
}