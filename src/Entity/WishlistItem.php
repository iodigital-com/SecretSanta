<?php

namespace App\Entity;

class WishlistItem
{
    private ?int $id = null;
    private Participant $participant;
    private ?string $description = '';
    private int $rank = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDescription(?string $description): self
    {
        if (null === $description) {
            $description = '';
        }
        $this->description = $description;

        return $this;
    }

    public function getDescription(): string
    {
        return (string) $this->description;
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
