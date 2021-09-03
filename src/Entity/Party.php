<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\PartyHasValidExcludes;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @PartyHasValidExcludes(groups={"exclude_participants"})
 */
class Party
{
    /** @var int */
    private $id;

    /** @var string */
    private $listurl;

    /**
     * The URL that is used to expose the wishlists for all participants of the party.
     *
     * @var string
     */
    private $wishlistsurl;

    private ?string $message = '';

    /** @var \DateTime */
    private $creationdate;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $ownerName;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $ownerEmail;

    /** @var \DateTime */
    private $sentdate;

    /**
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $eventdate;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @var ArrayCollection
     * @Assert\Valid()
     */
    private $participants;

    /** @var bool */
    private $created = false;

    /** @var string */
    private $locale = 'en';

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $location;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $confirmed;

    /** @var ?string */
    private $joinurl;

    /** @var int */
    private $joinmode = 0;

    private ?string $createdFromIp = null;

    public function __construct($createDefaults = true)
    {
        $this->participants = new ArrayCollection();

        if ($createDefaults) {
            $this->addEmptyParticipants($this);
        }

        $this->listurl = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->wishlistsurl = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->creationdate = new \DateTime();
        $this->message = '';
        $this->location = '';
        $this->joinurl = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setListurl(string $listurl): Party
    {
        $this->listurl = $listurl;

        return $this;
    }

    public function getListurl(): string
    {
        return $this->listurl;
    }

    public function setWishlistsurl(string $wishlistsurl): Party
    {
        $this->wishlistsurl = $wishlistsurl;

        return $this;
    }

    public function getWishlistsurl(): string
    {
        return $this->wishlistsurl;
    }

    public function setMessage(?string $message): Party
    {
        if (null === $message) {
            $message = '';
        }

        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return (string) $this->message;
    }

    public function getOwnerName(): string
    {
        return $this->ownerName;
    }

    public function setOwnerName(string $ownerName): Party
    {
        $this->ownerName = $ownerName;

        return $this;
    }

    public function getOwnerEmail(): string
    {
        return $this->ownerEmail;
    }

    public function setOwnerEmail(string $ownerEmail): Party
    {
        $this->ownerEmail = $ownerEmail;

        return $this;
    }

    public function setCreationDate(\DateTime $creationdate): Party
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationdate;
    }

    public function setSentdate(\DateTime $sentdate): Party
    {
        $this->sentdate = $sentdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSentdate()
    {
        return $this->sentdate;
    }

    public function addParticipant(Participant $participant): Party
    {
        $this->participants[] = $participant;

        return $this;
    }

    public function removeParticipant(Participant $participant)
    {
        $this->participants->removeElement($participant);
    }

    /**
     * @return Participant[]|ArrayCollection
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    public function __toString()
    {
        return 'Id: '.$this->id.' - Participants: '.$this->participants->count().' - Owner: '.$this->getOwnerName();
    }

    /**
     * @param \DateTime $eventdate
     *
     * @return Party
     */
    public function setEventdate($eventdate)
    {
        $this->eventdate = $eventdate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEventdate()
    {
        return $this->eventdate;
    }

    /**
     * @param string $amount
     *
     * @return Party
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param bool $created
     *
     * @return Party
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCreated()
    {
        return $this->created;
    }

    public function setLocale(string $locale): Party
    {
        $this->locale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function setLocation(?string $location)
    {
        if (null === $location) {
            $location = '';
        }

        $this->location = $location;
    }

    public function getConfirmed(): bool
    {
        return (bool) $this->confirmed;
    }

    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = (string) $confirmed;
    }

    public function getJoinurl(): ?string
    {
        return $this->joinurl;
    }

    public function setJoinurl(?string $joinurl): void
    {
        $this->joinurl = $joinurl;
    }

    public function getJoinmode(): int
    {
        return $this->joinmode;
    }

    public function setJoinmode(int $joinmode): void
    {
        $this->joinmode = $joinmode;
    }

    /**
     * @return array
     */
    public function createNewPartyForReuse()
    {
        $originalParty = $this;
        $countHashed = 0;
        $adminIsHashed = false;

        $party = new self(false);
        $party->setAmount($originalParty->getAmount());
        $party->setLocation($originalParty->getLocation());

        $originalParticipants = $originalParty->getParticipants();

        foreach ($originalParticipants as $originalParticipant) {
            if ($originalParticipant->isHashed()) {
                ++$countHashed;
                if ($originalParticipant->isPartyAdmin()) {
                    $adminIsHashed = true;
                }

                continue;
            }

            if ($adminIsHashed) {
                continue;
            }

            $participant = new Participant();
            $participant->setName($originalParticipant->getName());
            $participant->setEmail($originalParticipant->getEmail());
            $participant->setPartyAdmin($originalParticipant->isPartyAdmin());
            $party->addParticipant($participant);
        }

        $countParticipants = $party->getParticipants()->count();

        // When the admin is hashed, we will create a new empty list
        if ($adminIsHashed) {
            $countParticipants = 0;
            $countHashed = $originalParticipants->count();
        }

        $this->addEmptyParticipants($party, $countParticipants);

        return [$party, $countHashed];
    }

    /**
     * @param Party $party
     * @param int   $startParticipantsAmount
     */
    protected function addEmptyParticipants(self $party, $startParticipantsAmount = 0): void
    {
        // Create default minimum participants
        for ($i = $startParticipantsAmount; $i < 3; ++$i) {
            $participant = new Participant();
            if ($i === 0) {
                $participant->setPartyAdmin(true);
            }
            $party->addParticipant($participant);
        }
    }

    public function getCreatedFromIp(): ?string
    {
        return $this->createdFromIp;
    }

    public function setCreatedFromIp(string $createdFromIp): void
    {
        $this->createdFromIp = $createdFromIp;
    }
}
