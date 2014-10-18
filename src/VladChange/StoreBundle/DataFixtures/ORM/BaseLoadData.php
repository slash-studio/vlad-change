<?php

namespace VladChange\StoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use VladChange\StoreBundle\Entity\User;
use VladChange\StoreBundle\Entity\Placemark;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setName('Имя');
        $user->setSurname('Фамилия');
        $user->setEmail('vladchange@gmail.com');
        $user->setPlainPassword('test');
        $user->setEnabled(true);
        $userManager->updateUser($user, true);

        $fakePM = new Placemark();
        $fakePM->setName('Fake1')
               ->setLat(43.13416130704415)
               ->setLon(131.9348007202148)
               ->setLimitVoice(50)
               ->setShortDesc('Fake1 short description')
               ->setDesc('Fake1 long description')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();

        $user = $userManager->createUser();
        $user->setName('Имя1');
        $user->setSurname('Фамилия1');
        $user->setEmail('vladchange1@gmail.com');
        $user->setPlainPassword('test');
        $user->setEnabled(true);
        $userManager->updateUser($user, true);

        $fakePM = new Placemark();
        $fakePM->setName('Fake2')
               ->setLat(43.0853622332054)
               ->setLon(131.91969451904296)
               ->setLimitVoice(500)
               ->setShortDesc('Fake2 short description')
               ->setDesc('Fake2 long description')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();


    }
}