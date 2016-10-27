<?php

namespace App\FormationBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\FormationBundle\Entity\Media;

class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $urls = [
            'http://lorempicsum.com/nemo/255/200/5',
            'https://placekitten.com/g/200/300'
        ];

        for ($i = 0; $i < 10; $i++) {
            $image = new Media();
            $image->setTitle('image ' . $i);
            $image->setUrl($urls[$i%2]);
            $image->setAverage($i);
            $manager->persist($image);
            $this->addReference('media-' . $i, $image);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 10;
    }
}
