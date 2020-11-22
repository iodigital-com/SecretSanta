<?php

namespace Intracto\SecretSantaBundle\Entity;

class WishlistItem
{
    private int $id;
    private Participant $participant;
    private string $description;
    private string $image;
    private int $rank;

    public function getId(): int
    {
        return $this->id;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setRank(int $rank): self
    {
        $this->rank = $rank;

        return $this;
    }

    public function getRank(): int
    {
        return $this->rank;
    }

    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function setParticipant(Participant $participant): void
    {
        $this->participant = $participant;
    }
}
