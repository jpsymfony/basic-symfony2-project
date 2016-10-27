<?php

namespace App\FormationBundle\Tests\Entity\Manager;

use App\FormationBundle\Entity\Contact;
use App\FormationBundle\Entity\Manager\ContactManager;
use App\FormationBundle\Service\MailerService;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;

class ContactManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $templating;

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;

    /**
     * @var array
     */
    protected $template;

    /**
     * @var string $from
     */
    protected $from;

    /**
     * @var string $to
     */
    protected $to;

    /**
     * @var MailerService
     */
    protected $mailerService;

    /**
     * @var ContactManager
     */
    protected $contactManager;

    public function setUp()
    {
        $transport = $this->getMock(\Swift_Transport::class);
        $this->mailer = $this->getMock(\Swift_Mailer::class, [], [$transport]);
        $this->templating = $this->getMock(\Twig_Environment::class, ['render'], [], '', false);
        $this->translator = $this->getMock(Translator::class, ['trans'], [], '', false);
        $this->template = 'Bundle:Controller:Method';
        $this->from = 'from@test.fr';
        $this->to = 'to@test.fr';
        $this->mailerService = $this->getMock(MailerService::class, ['sendMail'], [$this->mailer]);
        $this->contactManager = new ContactManager($this->mailerService, $this->templating, $this->translator, $this->template, $this->from, $this->to);
    }

    public function testSendMail()
    {
        $contact = new Contact();
        $contact->setAdditionalInformation('some more information');
        $contact->setCellphone('0123456789');
        $contact->setEmail('contact.email@test.fr');
        $contact->setFirstName('firstName');
        $contact->setLastName('lastName');
        $contact->setKnowledge('pub_papier');

        $this->translator
            ->expects($this->exactly(2))
            ->method('trans')
            ->with('message_subject', ['%name%' => $contact->getFirstName() . ' ' . $contact->getLastName()], 'contact')
            ->willReturn('some translation');

        $this->templating
            ->expects($this->exactly(2))
            ->method('render')
            ->with($this->template, ['contact' => $contact])
            ->willReturn('The rendered template');

        $this->mailerService
            ->expects($this->once())
            ->method('sendMail')
            ->with(
                $this->from,
                $this->to,
                $this->translator->trans('message_subject', ['%name%' => $contact->getFirstName() . ' ' . $contact->getLastName()], 'contact'),
                $this->templating->render($this->template, ['contact' => $contact])
                );

        $this->contactManager->sendMail($contact);
    }
}