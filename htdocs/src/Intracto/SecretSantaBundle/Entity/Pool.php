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
     * @var string $listname
     *
     * @ORM\Column(name="listname", type="string", length=255)
     */
    private $listname;

    /**
     * @var string $message
     *
     * @ORM\Column(name="message", type="text")
     *
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @var string $sentdate
     *
     * @ORM\Column(name="sentdate", type="string", length=255, nullable=true)
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
     * Set listname
     *
     * @param string $listname
     * @return Pool
     */
    public function setListname($listname)
    {
        $this->listname = $listname;

        return $this;
    }

    /**
     * Get listname
     *
     * @return string
     */
    public function getListname()
    {
        return $this->listname;
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
     * @param string $sentdate
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
     * @return string
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
        return $this->getListname();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateListname()
    {
        $this->listname = time() . $this->entries->count() . $this->getOwnerName();
    }
}
