<?php

namespace AppBundle\Entity\Manager;

use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Entity\Vote;
use AppBundle\Repository\VoteRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class VoteManager
{
    /**
     * @var voteRepository $voteRepository
     */
    protected $voteRepository;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    public function __construct(VoteRepository $voteRepository, TokenStorageInterface $tokenStorage)
    {
        $this->voteRepository = $voteRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Get a new vote object for current user and given media
     *
     * @param Media $media
     * @return Vote|null
     */
    public function getNewVote(Media $media)
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof User) {
            return null;
        }

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);

        return $vote;
    }

    /**
     * Save a vote
     *
     * @param Vote $vote
     */
    public function saveVote(Vote $vote)
    {
        $media = $vote->getMedia();
        $media->addVote($vote);

        $this->voteRepository->save($vote);
    }
}