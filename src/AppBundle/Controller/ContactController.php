<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\Type\ContactType;
use AppBundle\Entity\Contact;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, new Contact());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.contact.manager')->sendMail($form->getData());
            $this->addFlash('success', 'Merci pour votre message.');
            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}
