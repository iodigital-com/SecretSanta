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
     * @var string $username
     *
     * @ORM\Column(name="username", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var string $sentdate
     *
     * @ORM\Column(name="sentdate", type="string", length=255, nullable=true)
     */
    private $sentdate;

    /**
     * @var ArrayCollection $entries
     *
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="pool")
     *
     * @Assert\Valid
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
     * Set username
     *
     * @param string $username
     * @return Pool
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Pool
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Pool
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
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
     * @return Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * @ORM\PrePersist
     */
    public function generatePassword()
    {
        $this->password = 'secret'; // @todo: generate random password
    }

    public function __toString()
    {
        return $this->getListname();
    }
}