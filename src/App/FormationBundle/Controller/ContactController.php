<?php

namespace App\FormationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use App\FormationBundle\Form\Type\ContactType;
use App\FormationBundle\Entity\Contact;

class ContactController extends Controller
{
    /**
     * @Route("/contact", name="contact")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(ContactType::class, new Contact());

        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app_formation.contact.manager')->sendMail($form->getData());
            $this->addFlash('success', 'Merci pour votre message.');
            return $this->redirectToRoute('contact');
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
