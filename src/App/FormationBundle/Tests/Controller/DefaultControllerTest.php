<?php

namespace App\FormationBundle\Tests\Controller;

// http://symfony.com/doc/current/book/testing.html for further details
use App\FormationBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class DefaultControllerTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    public function testIndex()
    {
        $client = static::createClient();
        $client->enableProfiler();

        $crawler = $client->request('GET', '/');

        $this->assertContains('Bonjour Eleve', $client->getResponse()->getContent());
        $this->assertGreaterThan(0, $crawler->filter('html:contains("Bonjour Eleve")')->count());

        // Check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the number of requests
            $this->assertEquals(
                0,
                $profile->getCollector('db')->getQueryCount()
            );

            // check the time spent in the framework
            $this->assertLessThan(
                300,
                $profile->getCollector('time')->getDuration()
            );
        }
    }

    public function testVotingMessage()
    {
        $this->loadFixtures(self::$kernel);
        $client = static::createClient();
        $client->enableProfiler();
        $client->followRedirects();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // go to a media page anonymously
        // find all links with the text "Media au hasard" and select the first one of the list
        $mediaLink = $crawler->filter('a:contains("Media au hasard")')->eq(0)->link();
        $crawler = $client->click($mediaLink);

        // a message should tell me to login in order to vote
        $this->assertEquals(1, $crawler->filter('html:contains("Vous devez être connecté pour voter.")')->count());

        // login
        $loginLink = $crawler->filter('a:contains("Se connecter")')->eq(0)->link();
        $crawler = $client->click($loginLink);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $form = $crawler->selectButton('login')->form();
        $form['_username'] = 'user1';
        $form['_password'] = 'password';

        $crawler = $client->submit($form);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // Check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the time spent in the framework
            $this->assertLessThan(
                1500,
                $profile->getCollector('time')->getDuration()
            );
        }

        $this->assertEquals(1, $crawler->filter('a:contains("Se déconnecter")')->count());

        //go to a media page authenticated
        $mediaLink = $crawler->filter('a:contains("Media au hasard")')->eq(0)->link();
        $crawler = $client->click($mediaLink);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // the message shouldn't be there anymore
        $this->assertEquals(0, $crawler->filter('html:contains("Vous devez être connecté pour voter.")')->count());
    }

    public function testShowTops()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tops');

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // Check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the time spent in the framework
            $this->assertLessThan(
                300,
                $profile->getCollector('time')->getDuration()
            );
        }

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Classement des tops")')->count());
    }

    public function testShowFlops()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/flops');

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // Check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the time spent in the framework
            $this->assertLessThan(
                300,
                $profile->getCollector('time')->getDuration()
            );
        }

        $this->assertGreaterThan(0, $crawler->filter('html:contains("Classement des flops")')->count());
    }
}
