<?php

namespace App\FormationBundle\Tests\Entity\Manager;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Entity\User;
use App\FormationBundle\Repository\MediaRepository;
use App\FormationBundle\Entity\Manager\MediaManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class MediaManagerTest extends \PHPUnit_Framework_TestCase
{
    protected $repository;
    protected $token;
    protected $tokenStorage;
    protected $mediaManager;

    public function setUp()
    {
        $this->token = $this->getMock(TokenInterface::class);
        $this->tokenStorage = $this->getMock(TokenStorageInterface::class);
        $this->repository = $this->getMock(MediaRepository::class, ['getNewMediaForUser', 'getRandomMedia', 'getHydratedMediaById', 'save'], [], "", false);
        $this->mediaManager = $this->getMock(MediaManager::class, ['save'], [$this->repository, $this->tokenStorage]);
    }

    public function testGetNextMedia()
    {
        $user = new User();
        $media = new Media();

        $this->token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->repository
            ->expects($this->once())
            ->method('getNewMediaForUser')
            ->willReturn($media);

        $this->repository
            ->expects($this->never())
            ->method('getRandomMedia');

        $this->assertEquals($media, $this->mediaManager->getNextMedia());
    }

    public function testGetNextMediaWillReturnGetRandomMediaRepositoryMethodIfNoMediaReturnedByGetNewMediaForUserMethod()
    {
        $user = new User();
        $media = new Media();

        $this->token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->repository
            ->expects($this->once())
            ->method('getNewMediaForUser')
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('getRandomMedia')
            ->willReturn($media);

        $this->assertEquals($media, $this->mediaManager->getNextMedia());
    }

    public function testGetNextMediaWillReturnGetRandomMediaRepositoryMethodIfNoConnectedUser()
    {
        $media = new Media();

        $this->token
            ->expects($this->once())
            ->method('getUser')
            ->willReturn(null);

        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($this->token);

        $this->repository
            ->expects($this->never())
            ->method('getNewMediaForUser');

        $this->repository
            ->expects($this->once())
            ->method('getRandomMedia')
            ->willReturn($media);

        $this->assertEquals($media, $this->mediaManager->getNextMedia());
    }

    public function testGetMedia()
    {
        $media = new Media();

        $this->repository
            ->expects($this->once())
            ->method('getHydratedMediaById')
            ->with(123456)
            ->willReturn($media);

        $this->assertEquals($media, $this->mediaManager->getMedia(123456));
    }

    public function testGetMediaWillReturnNull()
    {
        $this->repository
            ->expects($this->once())
            ->method('getHydratedMediaById')
            ->with(123456)
            ->willReturn(null);

        $this->assertNull($this->mediaManager->getMedia(123456));
    }

    public function testSaveMedia()
    {
        $media = new Media();

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($media);

        $this->mediaManager->saveMedia($media);
    }
}