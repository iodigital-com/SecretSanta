<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Validator\PoolHasValidExcludes;

/**
 * Intracto\SecretSantaBundle\Entity\Pool
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Intracto\SecretSantaBundle\Entity\PoolRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @PoolHasValidExcludes(groups={"exclude_entries"})
 */
class Pool
{
    /**
     * @var int $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $listurl
     *
     * @ORM\Column(name="listurl", type="string", length=255)
     */
    private $listurl;

    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime $creationdate
     *
     * @ORM\Column(name="creationdate", type="datetime", length=255)
     */
    private $creationdate;

    /**
     * @var \DateTime $sentdate
     *
     * @ORM\Column(name="sentdate", type="datetime", length=255, nullable=true)
     */
    private $sentdate;

    /**
     * @var \DateTime $eventdate
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="eventdate", type="datetime", length=255, nullable=true)
     */
    private $eventdate;

    /**
     * @var string $amount
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="amount", type="string", length=255, nullable=true)
     */
    private $amount;

    /**
     * @var ArrayCollection $entries
     *
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="pool", cascade={"persist", "remove"})
     *
     * @Assert\Valid()
     */
    private $entries;

    /**
     * @var bool $created
     *
     * @ORM\Column(name="created", type="boolean")
     */
    private $created = false;

    /**
     * @var string $locale
     *
     * @ORM\Column(name="locale", type="string", length=7)
     */
    private $locale = 'en';

    /**
     * @var bool $exposed
     *
     * @ORM\Column(name="exposed", type="boolean")
     */
    private $exposed = false;

    /**
     * @var string $location
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;

    public function __construct($createDefaults = true)
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();

        if($createDefaults) {
            // Create default minimum entries
            for ($i = 0; $i < 3; $i++) {
                $entry = new Entry();
                if ($i == 0) {
                    $entry->setPoolAdmin(true);
                }
                $this->addEntry($entry);
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
     * @return Pool
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
     * @return Pool
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
        return $this->entries->first()->getName();
    }

    /**
     * @return string
     */
    public function getOwnerEmail()
    {
        return $this->entries->first()->getEmail();
    }

    /**
     * @param $creationdate
     *
     * @return Pool
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
     * @return Pool
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
     * @param Entry $entry
     *
     * @return Pool
     */
    public function addEntry(Entry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * @param Entry $entry
     */
    public function removeEntry(Entry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    public function __toString()
    {
        return 'Id: ' . $this->id . ' - Entries: ' . $this->entries->count() . ' - Owner: ' . $this->getOwnerName();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateListurl()
    {
        $this->listurl = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

    /**
     * @param \Intracto\SecretSantaBundle\Entity\Entry $entries
     *
     * @return Pool
     */
    public function addEntrie(\Intracto\SecretSantaBundle\Entity\Entry $entries)
    {
        $this->entries[] = $entries;

        return $this;
    }

    /**
     * @param \Intracto\SecretSantaBundle\Entity\Entry $entries
     */
    public function removeEntrie(\Intracto\SecretSantaBundle\Entity\Entry $entries)
    {
        $this->entries->removeElement($entries);
    }

    /**
     * @param \DateTime $eventdate
     *
     * @return Pool
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
     * @return Pool
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
     * @return Pool
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
     * @return Pool
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
     * @return Pool
     */
    public function expose()
    {
        $this->exposed = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function getExposed()
    {
        return $this->exposed;
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

    public function createNewPoolForReuse()
    {
        $originalPool = $this;

        $pool = new Pool(false);
        $pool->setAmount($originalPool->getAmount());

        $originalEntries = $originalPool->getEntries();

        foreach ($originalEntries as $originalEntry) {
            $entry = new Entry();
            $entry->setName($originalEntry->getName());
            $entry->setEmail($originalEntry->getEmail());
            $entry->setPoolAdmin($originalEntry->isPoolAdmin());
            $pool->addEntry($entry);
        }

        return $pool;
    }
}
