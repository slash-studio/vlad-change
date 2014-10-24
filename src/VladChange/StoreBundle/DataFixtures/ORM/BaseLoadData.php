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

    private function addUser($userManager, $name, $surname, $email, $pass)
    {
        $user = $userManager->createUser();
        $user->setName($name)
             ->setSurname($surname)
             ->setEmail($email)
             ->setPlainPassword($pass)
             ->setEnabled(true);
        $userManager->updateUser($user, true);
        return $user;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $user = $this->addUser($userManager, 'Марк', 'Тертышный', 'm@gmail.com', 'test');
        $fakePM = new Placemark();
        $fakePM->setName('Давайте сделаем скейт парк')
               ->setLat(43.15416130704415)
               ->setLon(131.958007202148)
               ->setLimitVoice(50)
               ->setShortDesc('Тут очень много молодёжи, и мы любим кататься на скейтах, а рамты нет ((')
               ->setDesc('Тут очень много молодёжи, и мы любим кататься на скейтах, а рамты нет ((')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();

        $user = $this->addUser($userManager, 'Иван', 'Калита', 'i@gmail.com', 'test');
        $fakePM = new Placemark();
        $fakePM->setName('Книжный шкаф')
               ->setLat(43.1153622332054)
               ->setLon(131.91069451904296)
               ->setLimitVoice(500)
               ->setShortDesc('Ходим поставить книжный шкаф (как стоит в сквере на Суханова')
               ->setDesc('Ходим поставить книжный шкаф (как стоит в сквере на Суханова')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();

        $user = $this->addUser($userManager, 'Константин', 'Ландышев', 'k@gmail.com', 'test');
        $fakePM = new Placemark();
        $fakePM->setName('Поблизости нет ни одного магазина')
               ->setLat(43.0853622332054)
               ->setLon(131.91969451904296)
               ->setLimitVoice(500)
               ->setShortDesc('Вокруге нет ни одного магазина на много домов')
               ->setDesc('Вокруге нет ни одного магазина на много домов')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();

        $user = $this->addUser($userManager, 'Апанасенко', 'Александр', 'a@gmail.com', 'test');
        $fakePM = new Placemark();
        $fakePM->setName('Хотим играть в баскетбол')
               ->setLat(43.1253622332054)
               ->setLon(132.0000969451904296)
               ->setLimitVoice(500)
               ->setShortDesc('Давайте поставим баскетбольный щит и устроем дворовый чемпионат')
               ->setDesc('Давайте поставим баскетбольный щит и устроем дворовый чемпионат')
               ->setUser($user);
        $manager->persist($fakePM);
        $manager->flush();
    }
}