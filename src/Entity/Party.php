<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator\PartyHasValidExcludes;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @PartyHasValidExcludes(groups={"exclude_participants"})
 */
class Party
{
	// @phpstan-ignore-next-line
    private int $id;

    private string $listurl;

    /**
     * The URL that is used to expose the wishlists for all participants of the party.
     */
    private string $wishlistsurl;

    private ?string $message = '';

    private \DateTime $creationdate;

    private \DateTime $sentdate;

    /**
     * @Assert\NotBlank()
     */
    private \DateTime $eventdate;

    /**
     * @Assert\NotBlank()
     */
    private string $amount;

    /**
     * @var Collection<int, Participant>
     * @Assert\Valid()
     */
    private Collection $participants;

    private bool $created = false;

    private string $locale = 'en';

    /**
     * @Assert\NotBlank()
     */
    private string $location;

    /**
     * @Assert\NotBlank()
     */
    private string $confirmed;

    private ?string $joinurl;

    private int $joinmode = 0;

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

    public function getId(): int
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

    public function getMessage(): ?string
    {
        return (string) $this->message;
    }

    public function getOwnerName(): string
    {
        return $this->participants->first()->getName();
    }

    public function getOwnerEmail(): string
    {
        return $this->participants->first()->getEmail();
    }

    public function setCreationDate(\DateTime $creationdate): Party
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getCreationDate(): \DateTime
	{
        return $this->creationdate;
    }

    public function setSentdate(\DateTime $sentdate): Party
    {
        $this->sentdate = $sentdate;

        return $this;
    }

    public function getSentdate(): \DateTime
	{
        return $this->sentdate;
    }

    public function addParticipant(Participant $participant): Party
    {
        $this->participants->add($participant);

        return $this;
    }

    public function removeParticipant(Participant $participant): void
	{
        $this->participants->removeElement($participant);
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getParticipants(): Collection
	{
        return $this->participants;
    }

    public function __toString()
    {
        return 'Id: '.$this->id.' - Participants: '.$this->participants->count().' - Owner: '.$this->getOwnerName();
    }

    public function setEventdate(\DateTime $eventdate): Party
    {
        $this->eventdate = $eventdate;

        return $this;
    }

    public function getEventdate(): \DateTime
	{
        return $this->eventdate;
    }

    public function setAmount(string $amount): Party
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): string
	{
        return $this->amount;
    }

    public function setCreated(bool $created): Party
    {
        $this->created = $created;

        return $this;
    }

    public function getCreated(): bool
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

    public function setLocation(?string $location): void
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

    public function setConfirmed(bool $confirmed): void
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
    public function createNewPartyForReuse(): array
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

    protected function addEmptyParticipants(Party $party, int $startParticipantsAmount = 0): void
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
