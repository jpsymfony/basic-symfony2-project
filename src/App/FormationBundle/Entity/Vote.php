<?php

namespace App\FormationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vote
 *
 * @ORM\Table(name="vote", uniqueConstraints={@ORM\UniqueConstraint(name="vote_unique_idx", columns={"user_id", "media_id"})})
 * @ORM\Entity(repositoryClass="App\FormationBundle\Repository\VoteRepository")
 *
 * @UniqueEntity(fields={"user", "media"})
 */
class Vote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     * @Assert\NotBlank()
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\FormationBundle\Entity\User")
     *
     * @ORM\JoinColumn(
     *     name="user_id",
     *     referencedColumnName="id"
     * )
     */
    private $user;

    /**
     * @var Media $media
     *
     * @ORM\ManyToOne(
     *     targetEntity="App\FormationBundle\Entity\Media",
     *     inversedBy="votes")
     *
     * @ORM\JoinColumn(
     *     name="media_id",
     *     referencedColumnName="id"
     * )
     */
    private $media;

    public function __construct()
    {
        $this->date = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Vote
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Vote
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \App\FormationBundle\Entity\User $user
     *
     * @return Vote
     */
    public function setUser(\App\FormationBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \App\FormationBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set media
     *
     * @param \App\FormationBundle\Entity\Media $media
     *
     * @return Vote
     */
    public function setMedia(\App\FormationBundle\Entity\Media $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \App\FormationBundle\Entity\Media
     */
    public function getMedia()
    {
        return $this->media;
    }
}
