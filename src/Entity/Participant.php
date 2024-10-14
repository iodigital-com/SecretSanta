<?php

namespace App\Entity;

use App\Validator\ParticipantHasValidExcludes;
use App\Validator\ParticipantIsNotBlacklisted;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ParticipantHasValidExcludes(groups={"exclude_participants"})
 */
class Participant
{
    private int $id;

    private ?Party $party;

    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     mode="strict",
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     *
     * @ParticipantIsNotBlacklisted()
     */
    private string $email;

    private ?Participant $participant;

    /** @var Collection<int, Participant> */
    private Collection $excludedParticipants;

    private ?\DateTime $viewdate = null;

    private ?\DateTime $openEmailDate;

    private ?\DateTime $viewReminderSentTime;

    private string $url;

    /** @var Collection<int, WishlistItem> */
    private Collection $wishlistItems;

    private ?bool $wishlistUpdated = false;

    private ?\DateTime $updateWishlistReminderSentTime;

    private bool $partyAdmin = false;

    private ?string $ipv4;

    private ?string $ipv6;

    private ?string $geoCountry;

    private ?string $geoProvince;

    private ?string $geoCity;

    private ?\DateTime $partyStatusSentTime;

    private ?\DateTime $emptyWishlistReminderSentTime;

    private ?\DateTime $wishlistUpdatedTime;

    private bool $subscribedForUpdates = true;

    private bool $emailDidBounce = false;

    private ?\DateTime $invitationSentDate;

    private bool $isHashed = false;

    public function __construct()
    {
        $this->url = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->excludedParticipants = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParty(): ?Party
    {
        return $this->party;
    }

    public function setParty(?Party $party): Participant
    {
        $this->party = $party;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Participant
    {
        $this->name = preg_replace('/[^[:alnum:][:space:][\-_]]/u', '', $name);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Participant
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @deprecated use getAssignedParticipant()
     */
    public function getParticipant(): Participant
    {
        return $this->participant;
    }

    public function getAssignedParticipant(): ?Participant
    {
        return $this->participant;
    }

    public function setAssignedParticipant(?Participant $participant): Participant
    {
        $this->participant = $participant;

        return $this;
    }

    public function getViewdate(): ?\DateTime
    {
        return $this->viewdate;
    }

    public function setViewdate(?\DateTime $viewdate): Participant
    {
        $this->viewdate = $viewdate;

        return $this;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): Participant
    {
        $this->url = $url;

        return $this;
    }

    public function getWishlistUpdated(): ?bool
    {
        return $this->wishlistUpdated;
    }

    public function setWishlistUpdated(?bool $wishlistUpdated): Participant
    {
        $this->wishlistUpdated = $wishlistUpdated;

        return $this;
    }

    public function getUpdateWishlistReminderSentTime(): ?\DateTime
    {
        return $this->updateWishlistReminderSentTime;
    }

    public function setUpdateWishlistReminderSentTime(?\DateTime $updateWishlistReminderSentTime): void
    {
        $this->updateWishlistReminderSentTime = $updateWishlistReminderSentTime;
    }

    public function getViewReminderSentTime(): ?\DateTime
    {
        return $this->viewReminderSentTime;
    }

    public function setViewReminderSentTime(?\DateTime $viewReminderSentTime): void
    {
        $this->viewReminderSentTime = $viewReminderSentTime;
    }

    public function getWishlistItems(): Collection
    {
        return $this->wishlistItems;
    }

    /**
     * @param Collection<int, WishlistItem> $wishlistItems
     */
    public function setWishlistItems($wishlistItems): void
    {
        $this->wishlistItems = $wishlistItems;
    }

    public function addExcludedParticipant(Participant $excludedParticipant): Participant
    {
        $this->excludedParticipants->add($excludedParticipant);

        return $this;
    }

    public function removeExcludedParticipant(Participant $excludedParticipant): void
    {
        $this->excludedParticipants->removeElement($excludedParticipant);
    }

    public function getExcludedParticipants(): ArrayCollection|Collection
    {
        return $this->excludedParticipants;
    }

    public function isPartyAdmin(): bool
    {
        return $this->partyAdmin;
    }

    public function setPartyAdmin(bool $partyAdmin): void
    {
        $this->partyAdmin = $partyAdmin;
    }

    public function getIp(): ?string
    {
        if (null !== $this->getIpv4()) {
            return $this->getIpv4();
        }

        return $this->getIpv6();
    }

    public function setIp(?string $ip): void
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->setIpv4($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->setIpv6($ip);
        }
    }

    private function getIpv4(): ?string
    {
        return $this->ipv4;
    }

    private function setIpv4(?string $ipv4): Participant
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    private function getIpv6(): ?string
    {
        return $this->ipv6;
    }

    private function setIpv6(?string $ipv6): Participant
    {
        $this->ipv6 = $ipv6;

        return $this;
    }

    public function getGeoCountry(): ?string
    {
        return $this->geoCountry;
    }

    public function setGeoCountry(?string $geoCountry): Participant
    {
        $this->geoCountry = $geoCountry;

        return $this;
    }

    public function getGeoProvince(): ?string
    {
        return $this->geoProvince;
    }

    public function setGeoProvince(?string $geoProvince): Participant
    {
        $this->geoProvince = $geoProvince;

        return $this;
    }

    public function getGeoCity(): ?string
    {
        return $this->geoCity;
    }

    public function setGeoCity(?string $geoCity): Participant
    {
        $this->geoCity = $geoCity;

        return $this;
    }

    public function getPartyStatusSentTime(): \DateTime
    {
        return $this->partyStatusSentTime;
    }

    public function setPartyStatusSentTime(\DateTime $partyStatusSentTime): void
    {
        $this->partyStatusSentTime = $partyStatusSentTime;
    }

    public function getEmptyWishlistReminderSentTime(): ?\DateTime
    {
        return $this->emptyWishlistReminderSentTime;
    }

    public function setEmptyWishlistReminderSentTime(?\DateTime $emptyWishlistReminderSentTime): void
    {
        $this->emptyWishlistReminderSentTime = $emptyWishlistReminderSentTime;
    }

    public function getWishlistUpdatedTime(): ?\DateTime
    {
        return $this->wishlistUpdatedTime;
    }

    public function setWishlistUpdatedTime(?\DateTime $wishlistUpdatedTime): void
    {
        $this->wishlistUpdatedTime = $wishlistUpdatedTime;
    }

    public function unsubscribe(): void
    {
        $this->subscribedForUpdates = false;
    }

    public function subscribe(): void
    {
        $this->subscribedForUpdates = true;
    }

    public function isSubscribed(): bool
    {
        return $this->subscribedForUpdates;
    }

    public function getOpenEmailDate(): ?\DateTime
    {
        return $this->openEmailDate;
    }

    public function setOpenEmailDate(?\DateTime $openEmailDate): void
    {
        $this->openEmailDate = $openEmailDate;
    }

    public function getEmailDidBounce(): bool
    {
        return $this->emailDidBounce;
    }

    public function setEmailDidBounce(mixed $emailDidBounce): void
    {
        $this->emailDidBounce = $emailDidBounce;
    }

    public function getInvitationSentDate(): ?\DateTime
    {
        return $this->invitationSentDate;
    }

    public function setInvitationSentDate(?\DateTime $invitationSentDate): void
    {
        $this->invitationSentDate = $invitationSentDate;
    }

    public function isHashed(): bool
    {
        return $this->isHashed;
    }

    public function setIsHashed(bool $isHashed): void
    {
        $this->isHashed = $isHashed;
    }
}
