<?php

namespace App\FormationBundle\Tests\Controller;

// http://symfony.com/doc/current/book/testing.html for further details
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContactControllerTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
    }

    public function testSubmitForm()
    {
        $client = static::createClient();
        $client->enableProfiler();
        $crawler = $client->request('GET', '/contact');

        // a message should tell me to login in order to vote
        $this->assertEquals(1, $crawler->filter('html:contains("Formulaire de contact")')->count());

        $firstname = 'Mon prénom';
        $lastname ='Mon nom de famille';
        $cellphone = '06xxxxxxxx';
        $email = 'jpsymfony@free.fr';
        $additionalInformation = 'Pas grand chose à dire';
        $knowledge = 'facebook';
        $other = null;

        $form = $crawler->selectButton('envoyer')->form();
        $form['contact[firstName]'] = $firstname;
        $form['contact[lastName]'] = $lastname;
        $form['contact[cellphone]'] = $cellphone;
        $form['contact[email]'] = $email;
        $form['contact[additionalInformation]'] = $additionalInformation;
        $form['contact[knowledge]'] = $knowledge;
        $form['contact[other]'] = $other;

        $formData = [
            'firstName' => $firstname,
            'lastName' => $lastname,
            'cellphone' => $cellphone,
            'email' => $email,
            'additionalInformation' => $additionalInformation,
            'knowledge' => $knowledge,
            'other' => $other,
        ];

        $client->submit($form);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertEquals('Message de Mon prénom Mon nom de famille', $message->getSubject());
        $this->assertEquals($client->getContainer()->getParameter('email_from'), key($message->getFrom()));
        $this->assertEquals($client->getContainer()->getParameter('mailer_user'), key($message->getTo()));
        $this->assertEquals(
            $client->getContainer()->get('templating')->render('@AppFormation/Mail/contact_mail.html.twig', ['contact' => $formData]),
            $message->getBody()
        );

        $this->assertEquals($client->getContainer()->get('session')->getFlashBag()->get('success')[0], 'Merci pour votre message.');

        $client->followRedirect();

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        // Check that the profiler is enabled
        if ($profile = $client->getProfile()) {
            // check the number of requests
            $this->assertEquals(
                0,
                $profile->getCollector('db')->getQueryCount()
            );

            // check the time spent in the framework
            $this->assertLessThan(
                600,
                $profile->getCollector('time')->getDuration()
            );
        }
    }
}
