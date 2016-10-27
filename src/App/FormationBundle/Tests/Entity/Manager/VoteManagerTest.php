<?php

namespace App\FormationBundle\Tests\Entity\Manager;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Entity\Vote;
use App\FormationBundle\Repository\VoteRepository;
use App\FormationBundle\Entity\Manager\VoteManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class VoteManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $repository;
    protected $token;
    protected $tokenStorage;
    protected $voteManager;

    public function setUp()
    {
        $this->token = $this->getMock(TokenInterface::class);
        $this->tokenStorage = $this->getMock(TokenStorageInterface::class);
        $this->repository = $this->getMock(VoteRepository::class, ['save'], [], "", false);
        $this->voteManager = $this->getMock(VoteManager::class, ['save'], [$this->repository, $this->tokenStorage]);
    }

    public function testGetNewVote()
    {
        $user = new User();
        $media = new Media();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);

        $this->token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->assertEquals($vote, $this->voteManager->getNewVote($media));
    }

    public function testGetNewVoteReturnsNullIfNoConnectedUser()
    {
        $user = new User();
        $media = new Media();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);

        $this->token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->assertNull($this->voteManager->getNewVote($media));
    }

    public function testSaveVote()
    {
        $media = $this->getMock(Media::class);
        $vote = new Vote();
        $vote->setMedia($media);

        $media
            ->expects($this->once())
            ->method('addVote')
            ->with($vote);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($vote);

        $this->voteManager->saveVote($vote);
    }
}