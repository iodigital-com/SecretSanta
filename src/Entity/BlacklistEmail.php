<?php

namespace App\Entity;

class BlacklistEmail
{
	// @phpstan-ignore-next-line
    private int $id;
    private string $email;
    private ?string $ipv4;
    private ?string $ipv6;
    private ?\DateTime $date;

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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
	{
        $this->email = $email;
    }

    public function getIp(): string
    {
		return $this->getIpv4() ?? $this->getIpv6();
	}

    private function setIp(string $ip): void
	{
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $this->setIpv4($ip);
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $this->setIpv6($ip);
        }
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    private function setDate(?\DateTime $date): void
	{
        $this->date = $date;
    }

    public function getIpv4(): ?string
    {
        return $this->ipv4;
    }

    private function setIpv4(?string $ipv4): void
	{
        $this->ipv4 = $ipv4;
    }

    public function getIpv6(): ?string
    {
        return $this->ipv6;
    }

    private function setIpv6(?string $ipv6): void
	{
        $this->ipv6 = $ipv6;
    }
}
