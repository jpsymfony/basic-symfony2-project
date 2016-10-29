<?php

namespace AppBundle\Tests\Repository;

use AppBundle\Test\WebTestCase;
use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;

class MediaRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testGetHydratedMediaById()
    {
        $this->loadFixtures(self::$kernel);

        for ($i = 0; $i < 10; $i++) {
            $media = $this->em->getRepository(Media::class)->getHydratedMediaById($i + 1);
            $this->assertNotNull($media);
            $this->assertEquals('image ' . $i, $media->getTitle());
            $this->assertNotNull($media->getUrl());
            $this->assertNotNull($i, $media->getAverage());
            $this->assertEquals(2, count($media->getVotes()));
            $this->assertInstanceOf(Media::class, $media);
        }
    }

    public function testGetHydratedMediaByIdWillReturnNull()
    {
        $this->loadFixtures(self::$kernel);
        $media = $this->em->getRepository(Media::class)->getHydratedMediaById(9999);
        $this->assertNull($media);
    }

    public function testGetTopsMedia()
    {
        $this->loadFixtures(self::$kernel);
        $paginator = $this->em->getRepository(Media::class)->getTopsMedia();
        $this->assertNotNull($paginator);
        $this->assertInstanceOf(Paginator::class, $paginator);
        $dql = "SELECT m, v FROM AppBundle\Entity\Media m LEFT JOIN m.votes v WHERE m.average IS NOT NULL ORDER BY m.average DESC";
        $this->assertEquals($dql, $paginator->getQuery()->getDQL());
        $this->assertEquals(10, $paginator->count());
        $this->assertEquals(5, $paginator->getQuery()->getMaxResults());
        $this->assertEquals(0, $paginator->getQuery()->getFirstResult());
        $this->assertEquals(5, count($paginator->getQuery()->getScalarResult()));

        foreach ($paginator->getQuery()->getResult() as $media){
            $this->assertInstanceOf(Media::class, $media);
        }
    }

    public function testGetFlopsMedia()
    {
        $this->loadFixtures(self::$kernel);
        $paginator = $this->em->getRepository(Media::class)->getFlopsMedia();
        $this->assertNotNull($paginator);
        $this->assertInstanceOf(Paginator::class, $paginator);
        $dql = "SELECT m, v FROM AppBundle\Entity\Media m LEFT JOIN m.votes v WHERE m.average IS NOT NULL ORDER BY m.average ASC";
        $this->assertEquals($dql, $paginator->getQuery()->getDQL());
        $this->assertEquals(10, $paginator->count());
        $this->assertEquals(5, $paginator->getQuery()->getMaxResults());
        $this->assertEquals(0, $paginator->getQuery()->getFirstResult());
        $this->assertEquals(5, count($paginator->getQuery()->getScalarResult()));

        foreach ($paginator->getQuery()->getResult() as $media){
            $this->assertInstanceOf(Media::class, $media);
        }
    }

    public function testGetRandomMedia()
    {
        $this->loadFixtures(self::$kernel);
        $media = $this->em->getRepository(Media::class)->getRandomMedia();
        $this->assertNotNull($media);
        $this->assertInstanceOf(Media::class, $media);
    }

    public function testGetRandomMediaWillReturnNull()
    {
        $this->loadFixtures(self::$kernel, false);
        $media = $this->em->getRepository(Media::class)->getRandomMedia();
        $this->assertNull($media);
    }

    public function testGetNewMediaForUser()
    {
        $this->loadFixtures(self::$kernel);
        $user1 = $this->em->getRepository(User::class)->find(1);
        $user2 = $this->em->getRepository(User::class)->find(2);

        $resultsUser1 = $this->em->getRepository(Media::class)->getNewMediaForUser($user1);
        $resultsUser2 = $this->em->getRepository(Media::class)->getNewMediaForUser($user2);

        $this->assertNull($resultsUser1);
        $this->assertNotNull($resultsUser2);
        $this->assertInstanceOf(Media::class, $resultsUser2);
    }
}