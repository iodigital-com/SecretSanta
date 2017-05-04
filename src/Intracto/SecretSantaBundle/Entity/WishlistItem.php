<?php

namespace Intracto\SecretSantaBundle\Entity;

class WishlistItem
{
    /** @var int */
    private $id;

    /** @var Participant */
    private $participant;

    /** @var string */
    private $description;

    /** @var string */
    private $image;

    /** @var int */
    private $rank;

    /** @return int */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $description
     *
     * @return WishlistItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $image
     *
     * @return WishlistItem
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param int $rank
     *
     * @return WishlistItem
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * @return int
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * @return Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @param Participant $participant
     */
    public function setParticipant($participant)
    {
        $this->participant = $participant;
    }
}
