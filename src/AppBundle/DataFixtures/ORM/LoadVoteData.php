<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Vote;

class LoadVoteData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 10; $i++) {
            $media = $manager->merge($this->getReference('media-' . $i));

            $vote1 = new Vote();
            $vote1->setScore(rand(1, 10));
            $vote1->setUser($manager->merge($this->getReference('user-user1')));
            $media->addVote($vote1);

            $vote2 = new Vote();
            $vote2->setScore(rand(1, 10));
            $vote2->setUser($manager->merge($this->getReference('user-user3')));
            $media->addVote($vote2);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}
