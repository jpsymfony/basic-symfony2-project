<?php
namespace App\FormationBundle\Entity\Manager;

use App\FormationBundle\Entity\Contact;
use App\FormationBundle\Service\MailerService;
use Symfony\Component\Translation\TranslatorInterface;

class ContactManager
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
     * ContactManager constructor.
     * @param MailerService $mailerService
     * @param \Twig_Environment $templating
     * @param TranslatorInterface $translator
     * @param $template
     * @param $from
     * @param $to
     */
    public function __construct
    (
        MailerService $mailerService,
        \Twig_Environment $templating,
        TranslatorInterface $translator,
        $template,
        $from,
        $to
    )
    {
        $this->templating = $templating;
        $this->template = $template;
        $this->translator = $translator;
        $this->from = $from;
        $this->to = $to;
        $this->mailerService = $mailerService;
    }

    /**
     * @param Contact $contact
     */
    public function sendMail(Contact $contact)
    {
        $this->mailerService->sendMail(
            $this->from,
            $this->to,
            $this->translator->trans('message_subject', ['%name%' => $contact->getFirstName() . ' ' . $contact->getLastName()], 'contact'),
            $this->templating->render($this->template, ['contact' => $contact])
        );
    }
}
