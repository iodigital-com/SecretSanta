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
     *
     * @Assert\NotBlank()
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
     * @var string $owner_name
     *
     * @ORM\Column(name="owner_name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $owner_name;

    /**
     * @var string $owner_email
     *
     * @ORM\Column(name="owner_email", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $owner_email;

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
     * Set owner_name
     *
     * @param string $owner_name
     * @return Pool
     */
    public function setOwnerName($owner_name)
    {
        $this->owner_name = $owner_name;
    
        return $this;
    }

    /**
     * Get owner_name
     *
     * @return string 
     */
    public function getOwnerName()
    {
        return $this->owner_name;
    }

    /**
     * Set owner_email
     *
     * @param string $owner_email
     * @return Pool
     */
    public function setOwnerEmail($owner_email)
    {
        $this->owner_email = $owner_email;
    
        return $this;
    }

    /**
     * Get owner_email
     *
     * @return string 
     */
    public function getOwnerEmail()
    {
        return $this->owner_email;
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
}