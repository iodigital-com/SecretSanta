<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Validator\PartyHasValidExcludes;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="party", indexes={@ORM\Index(name="list_url", columns={"list_url"}),@ORM\Index(name="dates", columns={"created", "event_date", "sent_date"})})
 * @ORM\Entity(repositoryClass="Intracto\SecretSantaBundle\Entity\PartyRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @PartyHasValidExcludes(groups={"exclude_participants"})
 */
class Party
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
     * @var string
     *
     * @ORM\Column(name="list_url", type="string", length=255)
     */
    private $listurl;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation_date", type="datetime", length=255)
     */
    private $creationdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sent_date", type="datetime", length=255, nullable=true)
     */
    private $sentdate;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="event_date", type="datetime", length=255, nullable=true)
     */
    private $eventdate;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Participant", mappedBy="party", cascade={"persist", "remove"})
     *
     * @Assert\Valid()
     */
    private $participants;

    /**
     * @var bool
     *
     * @ORM\Column(name="created", type="boolean")
     */
    private $created = false;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string", length=7)
     */
    private $locale = 'en';

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

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
     * @param $creationdate
     *
     * @return Party
     */
    public function setCreationDate($creationdate)
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
     * @return Participant[]
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
     * @ORM\PrePersist
     */
    public function generateListurl()
    {
        $this->listurl = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
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

    public function createNewPartyForReuse()
    {
        $originalParty = $this;

        $party = new self(false);
        $party->setAmount($originalParty->getAmount());
        $party->setLocation($originalParty->getLocation());

        $originalParticipants = $originalParty->getParticipants();

        foreach ($originalParticipants as $originalParticipant) {
            $participant = new Participant();
            $participant->setName($originalParticipant->getName());
            $participant->setEmail($originalParticipant->getEmail());
            $participant->setPartyAdmin($originalParticipant->isPartyAdmin());
            $party->addParticipant($participant);
        }

        return $party;
    }
}
