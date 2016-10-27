<?php

namespace App\FormationBundle\Tests\Entity;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Entity\Vote;

class MediaTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNewAverageAfterVoteIfMediaHasVotes()
    {
        $media = new Media();

        $vote1 = new Vote();
        $vote1->setScore(5);

        $vote2 = new Vote();
        $vote2->setScore(9);

        $vote3 = new Vote();
        $vote3->setScore(2);

        $media->addVote($vote1);
        $media->addVote($vote2);
        $media->addVote($vote3);

        $media->getNewAverageAfterVote();

        $this->assertEquals(5.333333333333333, $media->getAverage());
    }

    public function testGetNewAverageAfterVoteIfMediaHasNoVotes()
    {
        $media = new Media();

        $media->getNewAverageAfterVote();

        $this->assertEquals(0, $media->getAverage());
    }

    /**
     * @param $actual
     * @param $expected
     * @dataProvider getDisplayedAverageProvider
     */
    public function testGetDisplayedAverage($actual, $expected)
    {
        $media = new Media();
        $media->setAverage($actual);

        $this->assertEquals($expected, $media->getDisplayedAverage());
    }

    public function getDisplayedAverageProvider()
    {
        return [
            [5, '5.0'],
            [5.5, '5.5'],
            [16/3, '5.3'],
            [null, '-'],
        ];
    }

    public function testHasUserAlreadyVoted()
    {
        $media = new Media();
        $user1 = new User();
        $user2 = new User();
        $user3 = new User();

        $vote1 = new Vote();
        $vote1->setUser($user1);

        $vote2 = new Vote();
        $vote2->setUser($user1);

        $vote3 = new Vote();
        $vote3->setUser($user2);

        $media->addVote($vote1);
        $media->addVote($vote2);
        $media->addVote($vote3);

        $this->assertTrue($media->hasUserAlreadyVoted($user1));
        $this->assertTrue($media->hasUserAlreadyVoted($user2));
        $this->assertFalse($media->hasUserAlreadyVoted($user3));
    }
}