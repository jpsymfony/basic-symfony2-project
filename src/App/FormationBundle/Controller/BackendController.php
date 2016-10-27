<?php

namespace App\FormationBundle\Controller;

use App\FormationBundle\Entity\Media;
use App\FormationBundle\Form\Type\MediaType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class BackendController extends Controller
{
    /**
     * @Route("/", name="backend_new_media")
     */
    public function newMediaAction(Request $request)
    {
        $media = new Media();
        $form = $this->createForm(MediaType::class, $media);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            if ($form->isValid())
            {
                $this->get('app_formation.media_manager')->saveMedia($media);
                $this->get('session')->getFlashBag()->add('success', 'Votre media est enregistré');

                return $this->redirectToRoute('backend_new_media');
            }
        }

        return $this->render('@AppFormation/Backend/newMedia.html.twig', [
            'form'  => $form->createView(),
        ]);
    }
}
