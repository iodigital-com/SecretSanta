<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Validator\EntryHasValidExcludes;

/**
 * Intracto\SecretSantaBundle\Entity\Entry.
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Intracto\SecretSantaBundle\Entity\EntryRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @EntryHasValidExcludes(groups={"exclude_entries"})
 */
class Entry
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
     * @var Pool
     *
     * @ORM\ManyToOne(targetEntity="Pool", inversedBy="entries")
     * @ORM\JoinColumn(name="poolId", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pool;

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
     */
    private $email;

    /**
     * @var Entry
     *
     * @ORM\OneToOne(targetEntity="Entry")
     * @ORM\JoinColumn(name="entryId", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    private $entry;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Entry")
     * @ORM\JoinTable(name="exclude",
     *      joinColumns={@ORM\JoinColumn(name="entryId", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="excludedEntryId", referencedColumnName="id")}
     *      )
     **/
    private $excluded_entries;

    /**
     * @var string
     *
     * @ORM\Column(name="wishlist", type="text", nullable=true)
     */
    private $wishlist;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="viewdate", type="datetime", nullable=true)
     */
    private $viewdate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="viewreminder_sent", type="datetime", nullable=true)
     */
    private $viewReminderSentTime;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="WishlistItem", mappedBy="entry", cascade={"persist", "remove"})
     * @ORM\OrderBy({"rank" = "asc"})
     */
    private $wishlistItems;

    /**
     * @var WishlistItem
     */
    private $removedWishlistItems;

    /**
     * @var bool
     *
     * @ORM\Column(name="wishlist_updated", type="boolean", nullable=true)
     */
    private $wishlist_updated = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatewishlistreminder_sent", type="datetime", nullable=true)
     */
    private $updateWishlistReminderSentTime;

    /**
     * @var bool
     *
     * @ORM\Column(name="poolAdmin", type="boolean", options={"default"=false})
     */
    private $poolAdmin = false;

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
     * @ORM\Column(name="poolstatus_sent", type="datetime", nullable=true)
     */
    private $poolStatusSentTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="emptywishlistreminder_sent", type="datetime", nullable=true)
     */
    private $emptyWishlistReminderSentTime;

    public function __construct()
    {
        $this->excluded_entries = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * @param Pool $pool
     *
     * @return Entry
     */
    public function setPool($pool)
    {
        $this->pool = $pool;

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
     * @return Entry
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return Entry
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getWishlist()
    {
        return $this->wishlist;
    }

    /**
     * @param string $wishlist
     *
     * @return Entry
     */
    public function setWishlist($wishlist)
    {
        if ($this->wishlist !== $wishlist) {
            $this->wishlist_updated = true;
        }

        $this->wishlist = $wishlist;

        return $this;
    }

    /**
     * @return Entry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * @param Entry $entry
     *
     * @return Entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;

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
     * @return Entry
     */
    public function setViewdate($viewdate)
    {
        $this->viewdate = $viewdate;

        return $this;
    }

    /**
     * @param string $secret
     *
     * @return Entry
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
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
     * @return Entry
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param bool $send
     *
     * @return Entry
     */
    public function setSend($send)
    {
        $this->send = $send;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSend()
    {
        return $this->send;
    }

    /**
     * @param bool $readyToSend
     *
     * @return Entry
     */
    public function setReadyToSend($readyToSend)
    {
        $this->ready_to_send = $readyToSend;

        return $this;
    }

    /**
     * @return bool
     */
    public function getReadyToSend()
    {
        return $this->ready_to_send;
    }

    /**
     * @return bool
     */
    public function getWishlistUpdated()
    {
        return $this->wishlist_updated;
    }

    /**
     * @param bool $wishlistUpdated
     *
     * @return Entry
     */
    public function setWishlistUpdated($wishlistUpdated)
    {
        $this->wishlist_updated = $wishlistUpdated;

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
     * @return WishlistItem
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
        $item->setEntry($this);
        $this->wishlistItems->add($item);
        $this->wishlist_updated = true;
    }

    public function removeWishlistItem(WishlistItem $item)
    {
        $this->removedWishlistItems->add($item);
        $item->setEntry(null);
        $this->wishlistItems->removeElement($item);
        $this->wishlist_updated = true;
    }

    /**
     * @return WishlistItem
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
     * @param \Intracto\SecretSantaBundle\Entity\Entry $excludedEntry
     *
     * @return Entry
     */
    public function addExcludedEntry(\Intracto\SecretSantaBundle\Entity\Entry $excludedEntry)
    {
        $this->excluded_entries[] = $excludedEntry;

        return $this;
    }

    /**
     * @param \Intracto\SecretSantaBundle\Entity\Entry $excludedEntry
     */
    public function removeExcludedEntrie(\Intracto\SecretSantaBundle\Entity\Entry $excludedEntry)
    {
        $this->excluded_entries->removeElement($excludedEntry);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getExcludedEntries()
    {
        return $this->excluded_entries;
    }

    /**
     * @return bool
     */
    public function isPoolAdmin()
    {
        return $this->poolAdmin;
    }

    /**
     * @param bool $poolAdmin
     */
    public function setPoolAdmin($poolAdmin)
    {
        $this->poolAdmin = $poolAdmin;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        if ($this->getIpv4() != null) {
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
     * @return Entry
     */
    private function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;

        return $ipv4;
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
     * @return Entry
     */
    private function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;

        return $ipv6;
    }

    /**
     * @return \DateTime
     */
    public function getPoolStatusSentTime()
    {
        return $this->poolStatusSentTime;
    }

    /**
     * @param \DateTime $poolStatusSentTime
     */
    public function setPoolStatusSentTime($poolStatusSentTime)
    {
        $this->poolStatusSentTime = $poolStatusSentTime;
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
}
