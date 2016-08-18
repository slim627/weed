<?php

namespace StaffBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use StaffBundle\Entity\AccessLevel;
use StaffBundle\Entity\Employee;
use StaffBundle\Entity\Notification;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $fosManipulator = $this->container->get('fos_user.util.user_manipulator');
        $grower = $this->container->get('fos_user.user_manager')->findUserByEmail('grower@admin.com');
        if(!$grower instanceof Employee){
            $grower = $fosManipulator->create('grower', 'grower', 'grower@admin.com', true, false);
        }

        $accessLevel = new AccessLevel();
        $accessLevel->setName('Grower');
        $accessLevel->setRole('ROLE_GROWER');
        $manager->persist($accessLevel);

        $grower->setAccessLevel($accessLevel);

        $accessLevel = new AccessLevel();
        $accessLevel->setName('Admin');
        $accessLevel->setRole('ROLE_ADMIN');
        $manager->persist($accessLevel);

        $notification = new Notification();
        $notification->setEmployee($grower);
        $notification->setIsViewed(false);
        $notification->setText('New patient registered');
        $manager->persist($notification);

        $notification = new Notification();
        $notification->setEmployee($grower);
        $notification->setIsViewed(false);
        $notification->setText('New order accepted');
        $manager->persist($notification);

        $manager->flush();
    }
}