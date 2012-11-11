<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Intracto\SecretSantaBundle\Entity\Pool;

/**
 * Intracto\SecretSantaBundle\Entity\Entry
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Entry
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
     * @var Pool $pool
     *
     * @ORM\ManyToOne(targetEntity="Pool")
     * @ORM\JoinColumn(name="poolId", referencedColumnName="id")
     *
     * @Assert\NotBlank()
     */
    private $pool;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var Entry $entry
     *
     * @ORM\OneToOne(targetEntity="Entry")
     * @ORM\JoinColumn(name="entryId", referencedColumnName="id", nullable=true)
     */
    private $entry;

    /**
     * @var \DateTime $viewdate
     *
     * @ORM\Column(name="viewdate", type="datetime", nullable=true)
     */
    private $viewdate;

    /**
     * @var string $secret
     *
     * @ORM\Column(name="secret", type="string", length=255)
     */
    private $secret;


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
     * Set pool
     *
     * @param Pool $pool
     * @return Entry
     */
    public function setPool($pool)
    {
        $this->pool = $pool;
    
        return $this;
    }

    /**
     * Get pool
     *
     * @return Pool
     */
    public function getPool()
    {
        return $this->pool;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Entry
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Entry
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
     * Set entry
     *
     * @param Entry $entry
     * @return Entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    
        return $this;
    }

    /**
     * Get entry
     *
     * @return Entry
     */
    public function getEntry()
    {
        return $this->entry;
    }

    /**
     * Set viewdate
     *
     * @param \DateTime $viewdate
     * @return Entry
     */
    public function setViewdate($viewdate)
    {
        $this->viewdate = $viewdate;
    
        return $this;
    }

    /**
     * Get viewdate
     *
     * @return \DateTime 
     */
    public function getViewdate()
    {
        return $this->viewdate;
    }

    /**
     * Set secret
     *
     * @param string $secret
     * @return Entry
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    
        return $this;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @ORM\PrePersist
     */
    public function generateSecret()
    {
        $this->secret = sha1($this->pool->getId() . $this->name . $this->email);
    }
}
