<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Party;
use App\Model\Exception\InvalidJoinLink;

/**
 * Details shown via the 'join link'.
 */
final class JoinLinkDetails
{
    public function __construct(
        private string $ownerName,
        private string $ownerEmail,
        private \DateTimeImmutable $eventDate,
        private string $amount,
        private string $location,
        private string $message,
        private string $locale,
    ) {}

    public static function fromParty(Party $party): self
    {
        if ($party->canJoinByLink()) {
            return new self(
                $party->getOwnerName(),
                $party->getOwnerEmail(),
                \DateTimeImmutable::createFromMutable($party->getEventdate()),
                $party->getAmount(),
                $party->getLocation(),
                $party->getMessage(),
                $party->getLocale(),
            );
        }

        throw InvalidJoinLink::forUrl($party->getJoinurl());
    }

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    public function getEventDate(): \DateTimeImmutable
    {
        return $this->eventDate;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }
}