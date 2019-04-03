<?php

namespace Intracto\SecretSantaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Validator\PartyHasValidExcludes;
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

    /** @var string */
    private $message;

    /** @var \DateTime */
    private $creationdate;

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

    public function __construct($createDefaults = true)
    {
        $this->participants = new ArrayCollection();

        if ($createDefaults) {
            // Create default minimum participants
            for ($i = 0; $i < 3; ++$i) {
                $participant = new Participant();
                if ($i === 0) {
                    $participant->setPartyAdmin(true);
                }
                $this->addParticipant($participant);
            }
        }

        $this->listurl = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->wishlistsurl = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->creationdate = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $listurl
     *
     * @return Party
     */
    public function setListurl($listurl)
    {
        $this->listurl = $listurl;

        return $this;
    }

    /**
     * @return string
     */
    public function getListurl()
    {
        return $this->listurl;
    }

    /**
     * @param string $wishlistsurl
     *
     * @return Party
     */
    public function setWishlistsurl($wishlistsurl)
    {
        $this->wishlistsurl = $wishlistsurl;

        return $this;
    }

    /**
     * @return string
     */
    public function getWishlistsurl()
    {
        return $this->wishlistsurl;
    }

    /**
     * @param string $message
     *
     * @return Party
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getOwnerName()
    {
        return $this->participants->first()->getName();
    }

    /**
     * @return string
     */
    public function getOwnerEmail()
    {
        return $this->participants->first()->getEmail();
    }

    /**
     * @param \DateTime $creationdate
     *
     * @return Party
     */
    public function setCreationDate(\DateTime $creationdate)
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

    /**
     * @param \DateTime $sentdate
     *
     * @return Party
     */
    public function setSentdate($sentdate)
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

    /**
     * @param Participant $participant
     *
     * @return Party
     */
    public function addParticipant(Participant $participant)
    {
        $this->participants[] = $participant;

        return $this;
    }

    /**
     * @param Participant $participant
     */
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

    /**
     * @param string $locale
     *
     * @return Party
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param string $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return bool
     */
    public function getConfirmed()
    {
        return (bool) $this->confirmed;
    }

    /**
     * @param bool $confirmed
     */
    public function setConfirmed(bool $confirmed)
    {
        $this->confirmed = (string) $confirmed;
    }

    public function createNewPartyForReuse()
    {
        $originalParty = $this;

        $party = new self(false);
        $party->setAmount($originalParty->getAmount());
        $party->setLocation($originalParty->getLocation());

        $originalParticipants = $originalParty->getParticipants();

        foreach ($originalParticipants as $originalParticipant) {
            if ($originalParticipant->isHashed()){
                continue;
            }
            $participant = new Participant();
            $participant->setName($originalParticipant->getName());
            $participant->setEmail($originalParticipant->getEmail());
            $participant->setPartyAdmin($originalParticipant->isPartyAdmin());
            $party->addParticipant($participant);
        }

        return $party;
    }
}
