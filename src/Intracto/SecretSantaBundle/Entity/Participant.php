<?php

namespace Intracto\SecretSantaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Intracto\SecretSantaBundle\Validator\ParticipantHasValidExcludes;
use Intracto\SecretSantaBundle\Validator\ParticipantIsNotBlacklisted;

/**
 * @ParticipantHasValidExcludes(groups={"exclude_participants"})
 */
class Participant
{
    /** @var int */
    private $id;

    /** @var Party */
    private $party;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email(
     *     strict=true,
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @ParticipantIsNotBlacklisted()
     */
    private $email;

    /** @var Participant */
    private $participant;

    /** @var ArrayCollection */
    private $excludedParticipants;

    /** @var \DateTime */
    private $viewdate;

    /** @var \DateTime */
    private $openEmailDate;

    /** @var \DateTime */
    private $viewReminderSentTime;

    /** @var string */
    private $url;

    /** @var WishlistItem[] */
    private $wishlistItems;

    /** @var bool */
    private $wishlistUpdated = false;

    /** @var \DateTime */
    private $updateWishlistReminderSentTime;

    /** @var bool */
    private $partyAdmin = false;

    /** @var string */
    private $ipv4;

    /** @var string */
    private $ipv6;

    /** @var string */
    private $geoCountry;

    /** @var string */
    private $geoProvince;

    /** @var string */
    private $geoCity;

    /** @var \DateTime */
    private $partyStatusSentTime;

    /** @var \DateTime */
    private $emptyWishlistReminderSentTime;

    /** @var \DateTime */
    private $wishlistUpdatedTime;

    /** @var bool */
    private $subscribedForUpdates = true;

    /** @var bool */
    private $emailDidBounce = false;

    /** @var \DateTime */
    private $invitationSentDate;

    public function __construct()
    {
        $this->url = base_convert(sha1(uniqid((string) mt_rand(), true)), 16, 36);
        $this->excludedParticipants = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Party
     */
    public function getParty()
    {
        return $this->party;
    }

    /**
     * @param Party $party
     *
     * @return Participant
     */
    public function setParty($party)
    {
        $this->party = $party;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Participant
     */
    public function setName($name)
    {
        $this->name = preg_replace('/[^[:alnum:][:space:][\-_]]/u', '', $name);

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Participant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Participant
     *
     * @deprecated use getAssignedParticipant()
     */
    public function getParticipant()
    {
        return $this->participant;
    }

    /**
     * @return Participant
     */
    public function getAssignedParticipant()
    {
        return $this->participant;
    }

    /**
     * @param Participant $participant
     *
     * @return Participant
     */
    public function setAssignedParticipant($participant)
    {
        $this->participant = $participant;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getViewdate()
    {
        return $this->viewdate;
    }

    /**
     * @param \DateTime $viewdate
     *
     * @return Participant
     */
    public function setViewdate($viewdate)
    {
        $this->viewdate = $viewdate;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return Participant
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return bool
     */
    public function getWishlistUpdated()
    {
        return $this->wishlistUpdated;
    }

    /**
     * @param bool $wishlistUpdated
     *
     * @return Participant
     */
    public function setWishlistUpdated($wishlistUpdated)
    {
        $this->wishlistUpdated = $wishlistUpdated;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdateWishlistReminderSentTime()
    {
        return $this->updateWishlistReminderSentTime;
    }

    /**
     * @param \DateTime $updateWishlistReminderSentTime
     */
    public function setUpdateWishlistReminderSentTime($updateWishlistReminderSentTime)
    {
        $this->updateWishlistReminderSentTime = $updateWishlistReminderSentTime;
    }

    /**
     * @return \DateTime
     */
    public function getViewReminderSentTime()
    {
        return $this->viewReminderSentTime;
    }

    /**
     * @param \DateTime $viewReminderSentTime
     */
    public function setViewReminderSentTime($viewReminderSentTime)
    {
        $this->viewReminderSentTime = $viewReminderSentTime;
    }

    /**
     * @return ArrayCollection|WishlistItem[]
     */
    public function getWishlistItems()
    {
        return $this->wishlistItems;
    }

    /**
     * @param WishlistItem[] $wishlistItems
     */
    public function setWishlistItems($wishlistItems)
    {
        $this->wishlistItems = $wishlistItems;
    }

    /**
     * @param Participant $excludedParticipant
     *
     * @return Participant
     */
    public function addExcludedParticipant(self $excludedParticipant)
    {
        $this->excludedParticipants[] = $excludedParticipant;

        return $this;
    }

    /**
     * @param Participant $excludedParticipant
     */
    public function removeExcludedParticipant(self $excludedParticipant)
    {
        $this->excludedParticipants->removeElement($excludedParticipant);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExcludedParticipants()
    {
        return $this->excludedParticipants;
    }

    /**
     * @return bool
     */
    public function isPartyAdmin()
    {
        return $this->partyAdmin;
    }

    /**
     * @param bool $partyAdmin
     */
    public function setPartyAdmin($partyAdmin)
    {
        $this->partyAdmin = $partyAdmin;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        if ($this->getIpv4() !== null) {
            return $this->getIpv4();
        }

        return $this->getIpv6();
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->setIpv4($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->setIpv6($ip);
        }
    }

    /**
     * @return string
     */
    private function getIpv4()
    {
        return $this->ipv4;
    }

    /**
     * @param string $ipv4
     *
     * @return Participant
     */
    private function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $this;
    }

    /**
     * @return string
     */
    private function getIpv6()
    {
        return $this->ipv6;
    }

    /**
     * @param string $ipv6
     *
     * @return Participant
     */
    private function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoCountry()
    {
        return $this->geoCountry;
    }

    /**
     * @param string $geoCountry
     *
     * @return Participant
     */
    public function setGeoCountry($geoCountry)
    {
        $this->geoCountry = $geoCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoProvince()
    {
        return $this->geoProvince;
    }

    /**
     * @param string $geoProvince
     *
     * @return Participant
     */
    public function setGeoProvince($geoProvince)
    {
        $this->geoProvince = $geoProvince;

        return $this;
    }

    /**
     * @return string
     */
    public function getGeoCity()
    {
        return $this->geoCity;
    }

    /**
     * @param string $geoCity
     *
     * @return Participant
     */
    public function setGeoCity($geoCity)
    {
        $this->geoCity = $geoCity;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPartyStatusSentTime()
    {
        return $this->partyStatusSentTime;
    }

    /**
     * @param \DateTime $partyStatusSentTime
     */
    public function setPartyStatusSentTime($partyStatusSentTime)
    {
        $this->partyStatusSentTime = $partyStatusSentTime;
    }

    /**
     * @return \DateTime
     */
    public function getEmptyWishlistReminderSentTime()
    {
        return $this->emptyWishlistReminderSentTime;
    }

    /**
     * @param \DateTime $emptyWishlistReminderSentTime
     */
    public function setEmptyWishlistReminderSentTime($emptyWishlistReminderSentTime)
    {
        $this->emptyWishlistReminderSentTime = $emptyWishlistReminderSentTime;
    }

    /**
     * @return \DateTime
     */
    public function getWishlistUpdatedTime()
    {
        return $this->wishlistUpdatedTime;
    }

    /**
     * @param \DateTime $wishlistUpdatedTime
     */
    public function setWishlistUpdatedTime($wishlistUpdatedTime)
    {
        $this->wishlistUpdatedTime = $wishlistUpdatedTime;
    }

    public function unsubscribe()
    {
        $this->subscribedForUpdates = false;
    }

    public function subscribe()
    {
        $this->subscribedForUpdates = true;
    }

    public function isSubscribed()
    {
        return $this->subscribedForUpdates;
    }

    /**
     * @return \DateTime
     */
    public function getOpenEmailDate()
    {
        return $this->openEmailDate;
    }

    /**
     * @param \DateTime $openEmailDate
     */
    public function setOpenEmailDate($openEmailDate)
    {
        $this->openEmailDate = $openEmailDate;
    }

    /**
     * @return mixed
     */
    public function getEmailDidBounce()
    {
        return $this->emailDidBounce;
    }

    /**
     * @param mixed $emailDidBounce
     */
    public function setEmailDidBounce($emailDidBounce)
    {
        $this->emailDidBounce = $emailDidBounce;
    }

    /**
     * @return \DateTime
     */
    public function getInvitationSentDate()
    {
        return $this->invitationSentDate;
    }

    /**
     * @param \DateTime $invitationSentDate
     */
    public function setInvitationSentDate($invitationSentDate)
    {
        $this->invitationSentDate = $invitationSentDate;
    }
}
