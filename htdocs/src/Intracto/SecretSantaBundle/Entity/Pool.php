<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Entity\Entry;

/**
 * Intracto\SecretSantaBundle\Entity\Pool
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Pool
{
    /**
     * @var integer $id
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
     *
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @var datetime $sentdate
     *
     * @ORM\Column(name="sentdate", type="datetime", length=255, nullable=true)
     */
    private $sentdate;

    /**
     * @var ArrayCollection $entries
     *
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="pool", cascade={"persist", "remove"})
     *
     * @Assert\Valid()
     */
    private $entries;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
        // Create default minimum entries
        $default_number_of_entries = 3;
        $i = 0;
        while ($i < $default_number_of_entries) {
            $entry = new Entry();
            $this->addEntry($entry);
            $i++;
        }
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set listurl
     *
     * @param string $listurl
     * @return Pool
     */
    public function setListurl($listurl)
    {
        $this->listurl = $listurl;

        return $this;
    }

    /**
     * Get listurl
     *
     * @return string
     */
    public function getListurl()
    {
        return $this->listurl;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return Pool
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Get owner_name
     *
     * @return string
     */
    public function getOwnerName()
    {
        return $this->entries->first()->getName();
    }

    /**
     * Get owner_email
     *
     * @return string
     */
    public function getOwnerEmail()
    {
        return $this->entries->first()->getEmail();
    }

    /**
     * Set sentdate
     *
     * @param datetime $sentdate
     * @return Pool
     */
    public function setSentdate($sentdate)
    {
        $this->sentdate = $sentdate;

        return $this;
    }

    /**
     * Get sentdate
     *
     * @return datetime
     */
    public function getSentdate()
    {
        return $this->sentdate;
    }

    /**
     * Add entry
     *
     * @param Entry $entry
     * @return Pool
     */
    public function addEntry(Entry $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param Entry $entry
     */
    public function removeEntry(Entry $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    public function __toString()
    {
        return "Id: " . $this->id . " - Entries: " . $this->entries->count() . " - Owner: " . $this->getOwnerName();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateListurl()
    {
        $this->listurl = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }

}
