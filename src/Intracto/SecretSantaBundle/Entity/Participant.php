<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Validator\ParticipantHasValidExcludes;
use Doctrine\Common\Collections\ArrayCollection;
use Intracto\SecretSantaBundle\Validator\ParticipantIsNotBlacklisted;

/**
 * @ORM\Table(name="participant", indexes={@ORM\Index(name="participant_url", columns={"url"})})
 * @ORM\Entity(repositoryClass="Intracto\SecretSantaBundle\Entity\ParticipantRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @ParticipantHasValidExcludes(groups={"exclude_participants"})
 */
class Participant
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
     * @var Party
     *
     * @ORM\ManyToOne(targetEntity="Party", inversedBy="participants")
     * @ORM\JoinColumn(name="party_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $party;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     strict=true,
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     * @ParticipantIsNotBlacklisted()
     */
    private $email;

    /**
     * @var Participant
     *
     * @ORM\OneToOne(targetEntity="Participant")
     * @ORM\JoinColumn(name="assigned_participant_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $participant;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Participant")
     * @ORM\JoinTable(name="exclude",
     *      joinColumns={@ORM\JoinColumn(name="participant_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="excluded_participant_id", referencedColumnName="id")}
     *      )
     **/
    private $excludedParticipants;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="view_date", type="datetime", nullable=true)
     */
    private $viewdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="open_email_date", type="datetime", nullable=true)
     */
    private $openEmailDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="view_reminder_sent", type="datetime", nullable=true)
     */
    private $viewReminderSentTime;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var WishlistItem[]
     *
     * @ORM\OneToMany(targetEntity="WishlistItem", mappedBy="participant", cascade={"persist", "remove"})
     * @ORM\OrderBy({"rank" = "asc"})
     */
    private $wishlistItems;

    /**
     * @var WishlistItem[]
     */
    private $removedWishlistItems;

    /**
     * @var bool
     *
     * @ORM\Column(name="wishlist_updated", type="boolean", nullable=true)
     */
    private $wishlistUpdated = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_wishlist_reminder_sent", type="datetime", nullable=true)
     */
    private $updateWishlistReminderSentTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="party_admin", type="boolean", options={"default"=false})
     */
    private $partyAdmin = false;

    /**
     * @var string
     *
     * @ORM\Column(name="ipv4", type="string", nullable=true)
     */
    private $ipv4;

    /**
     * @var string
     *
     * @ORM\Column(name="ipv6", type="string", nullable=true)
     */
    private $ipv6;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="party_status_sent", type="datetime", nullable=true)
     */
    private $partyStatusSentTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="empty_wishlist_reminder_sent", type="datetime", nullable=true)
     */
    private $emptyWishlistReminderSentTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="wishlist_updated_time", type="datetime", nullable=true)
     */
    private $wishlistUpdatedTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="subscribed_for_updates", type="boolean", options={"default"=true})
     */
    private $subscribedForUpdates = true;

    public function __construct()
    {
        $this->excludedParticipants = new ArrayCollection();
        $this->postLoad();
    }

    /**
     * @ORM\PostLoad
     */
    public function postLoad()
    {
        $this->removedWishlistItems = new ArrayCollection();
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
        $this->name = preg_replace('/[^[:alnum:][:space:]]/u', '', $name);

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
     * @return WishlistItem[]
     */
    public function getWishlistItems()
    {
        return $this->wishlistItems;
    }

    /**
     * @param WishlistItem $wishlistItems
     */
    public function setWishlistItems($wishlistItems)
    {
        $this->wishlistItems = $wishlistItems;
    }

    public function addWishlistItem(WishlistItem $item)
    {
        $this->removedWishlistItems->removeElement($item);
        $item->setParticipant($this);
        $this->wishlistItems->add($item);
        $this->wishlistUpdated = true;
    }

    public function removeWishlistItem(WishlistItem $item)
    {
        $this->removedWishlistItems->add($item);
        $item->setParticipant(null);
        $this->wishlistItems->removeElement($item);
        $this->wishlistUpdated = true;
    }

    /**
     * @return WishlistItem[]
     */
    public function getRemovedWishlistItems()
    {
        return $this->removedWishlistItems;
    }

    /**
     * @param WishlistItem $removedWishlistItems
     */
    public function setRemovedWishlistItems($removedWishlistItems)
    {
        $this->removedWishlistItems = $removedWishlistItems;
    }

    /**
     * @param Participant $excludedParticipant
     *
     * @return Participant
     */
    public function addExcludedParticipant(Participant $excludedParticipant)
    {
        $this->excludedParticipants[] = $excludedParticipant;

        return $this;
    }

    /**
     * @param Participant $excludedParticipant
     */
    public function removeExcludedParticipant(Participant $excludedParticipant)
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

}
