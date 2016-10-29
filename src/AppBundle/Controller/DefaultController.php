<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Manager\MediaManager;
use AppBundle\Entity\Manager\VoteManager;
use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Vote;
use AppBundle\Form\Type\VoteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig',
            [
                'name' => "Eleve",
                'days' => ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi'],
                'html' => "<b>ce texte n'est pas en gras!</b>",
            ]);
    }

    /**
     * @Route("/show-random", name="show_random")
     */
    public function showRandomMediaAction()
    {
        //$entityManager = $this->getDoctrine()->getManager();
        //$mediaRepository = $entityManager->getRepository('AppBundle:Media');
        //$media = $mediaRepository->getRandomMedia();
        $media = $this->getMediaManager()->getNextMedia();

        return $this->redirect($this->generateUrl('show_media', ['id' => $media->getId()]));
    }

    /**
     * @Route("/show/{id}", name="show_media")
     */
    public function showMediaAction($id)
    {
        //$em = $this->getDoctrine()->getManager();
        //$media = $em
        //    ->getRepository('AppBundle:Media')
            //->findOneById($id);
        //    ->getHydratedMediaById($id);

        $media = $this->getMediaManager()->getMedia($id);

        if (null === $media){
            throw $this->createNotFoundException();
        }

        $user = $this->getUser();

        // if user is not connected
        if (!$user instanceof User) {
            $form = null;
        }

        /*$vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);*/
        $vote = $this->getVoteManager()->getNewVote($media);

        if ($vote instanceof Vote) {
            $form = $this->createForm(VoteType::class, $vote);
        }

        return $this->render('default/show.html.twig', [
            'media' => $media,
            'form'  => isset($form) ? $form->createView() : null,
        ]);
    }

    /**
     * @Route("/tops", name="show_tops")
     */
    public function showTopsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $tops = $em->getRepository(Media::class)->getTopsMedia();

        return $this->render('default/tops.html.twig', [
            'tops' => $tops,
        ]);
    }

    /**
     * @Route("/flops", name="show_flops")
     */
    public function showFlopsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $flops = $em->getRepository(Media::class)->getFlopsMedia();

        return $this->render('default/flops.html.twig', [
            'flops' => $flops,
        ]);
    }

    /**
     * @Route("/vote/{id}", name="vote_media")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function voteMediaAction(Request $request, $id)
    {
        $media = $this->getMediaManager()->getMedia($id);

        if (null === $media){
            throw $this->createNotFoundException();
        }

        /*$user = $this->getUser();
        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);*/
        $vote = $this->getVoteManager()->getNewVote($media);
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isValid())
        {
            /*$em = $this->getDoctrine()->getManager();
            $media = $vote->getMedia();
            $media->addVote($vote);
            $em->persist($vote);
            $em->flush();*/
            $this->getVoteManager()->saveVote($vote);

            $this->get('session')->getFlashBag()->add('success', 'Votre vote est enregistré');

            return $this->redirectToRoute('show_media', ['id' => $media->getId()]);
        }

        $this->get('session')->getFlashBag()->add('error', 'Une erreur est survenue');

        return $this->forward('AppBundle:Default:showMedia', ['id' => $media->getId()]);
    }

    /**
     * @return MediaManager
     */
    public function getMediaManager()
    {
        return $this->get('app.media_manager');
    }

    /**
     * @return VoteManager
     */
    public function getVoteManager()
    {
        return $this->get('app.vote_manager');
    }
}
