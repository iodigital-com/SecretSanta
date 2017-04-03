<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blacklist_item")
 * @ORM\Entity
 */
class BlacklistItem
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
     * @var email
     *
     * @ORM\Column(name="email", type="text", nullable=false)
     */
    private $email;

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
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param email $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getIpv4()
    {
        return $this->ipv4;
    }

    /**
     * @param string $ipv4
     */
    public function setIpv4($ipv4)
    {
        $this->ipv4 = $ipv4;
    }

    /**
     * @return string
     */
    public function getIpv6()
    {
        return $this->ipv6;
    }

    /**
     * @param string $ipv6
     */
    public function setIpv6($ipv6)
    {
        $this->ipv6 = $ipv6;
    }
}
