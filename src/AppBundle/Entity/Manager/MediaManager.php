<?php

namespace AppBundle\Entity\Manager;

use AppBundle\Entity\Media;
use AppBundle\Entity\User;
use AppBundle\Repository\MediaRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MediaManager
{
    /**
     * @var MediaRepository $mediaRepository
     */
    protected $mediaRepository;

    /**
     * @var TokenStorageInterface $tokenStorage
     */
    protected $tokenStorage;

    public function __construct(MediaRepository $mediaRepository, TokenStorageInterface $tokenStorage)
    {
        $this->mediaRepository = $mediaRepository;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Get the next media to display depending on the user
     *
     * @return Media|null
     */
    public function getNextMedia()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if ($user instanceof User) {
            $media = $this->mediaRepository->getNewMediaForUser($user);

            if ($media instanceof Media) {
                return $media;
            }
        }

        return $this->mediaRepository->getRandomMedia();
    }

    /**
     * Get a media object from an id, with votes hydrated
     *
     * @param $id
     * @return Media
     */
    public function getMedia($id)
    {
        $media = $this->mediaRepository->getHydratedMediaById($id);

        return $media instanceof Media ? $media : null;
    }

    /**
     * Save a media
     *
     * @param Media $media
     */
    public function saveMedia(Media $media)
    {
        $this->mediaRepository->save($media);
    }
}