<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /** @var ContainerInterface */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $factory = $this->container->get('security.encoder_factory');

        $userDatas = [
            'user1' => [
                'email' => 'user1@yoyo.com',
                'password' => 'password',
            ],
            'user2' => [
                'email' => 'user2@yoyo.com',
                'password' => 'password',
            ],
            'user3' => [
                'email' => 'user3@yoyo.com',
                'password' => 'password',
            ],
        ];

        foreach ($userDatas as $userName => $userData) {
            $user = new User();
            $user->setUsername($userName);
            $user->setEmail($userData['email']);
            $user->setPlainPassword($userData['password']);
            $encoder = $factory->getEncoder($user);
            $user->encodePassword($encoder);
            $manager->persist($user);
            $this->addReference(sprintf('user-%s', $userName), $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }
}
