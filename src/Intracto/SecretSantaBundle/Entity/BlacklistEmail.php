<?php

namespace Intracto\SecretSantaBundle\Entity;

class BlacklistEmail
{
    private int $id;
    private string $email;
    private string $ipv4;
    private string $ipv6;
    private \DateTime $date;

    public function __construct(string $hashedEmail, string $ip, \DateTime $date)
    {
        $this->setIp($ip);
        $this->setEmail($hashedEmail);
        $this->setDate($date);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): email
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getIp(): string
    {
        if ($this->getIpv4() !== null) {
            return $this->getIpv4();
        }

        return $this->getIpv6();
    }

    private function setIp(string $ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->setIpv4($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->setIpv6($ip);
        }
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    private function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    public function getIpv4(): string
    {
        return $this->ipv4;
    }

    private function setIpv4(string $ipv4)
    {
        $this->ipv4 = $ipv4;
    }

    public function getIpv6(): string
    {
        return $this->ipv6;
    }

    private function setIpv6(string $ipv6)
    {
        $this->ipv6 = $ipv6;
    }
}
