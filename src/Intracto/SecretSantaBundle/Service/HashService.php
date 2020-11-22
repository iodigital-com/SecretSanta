<?php

namespace Intracto\SecretSantaBundle\Service;

class HashService
{
    private string $salt;

    public function __construct(
        string $salt
    ) {
        $this->salt = $salt;
    }

    public function hashString($string): string
    {
        return sha1($string.$this->salt);
    }

    public function hashEmail($email): string
    {
        return $this->hashString($email).'@example.com';
    }

    public function isAlreadyHashed(string $email): bool
    {
        $needle = '@example.com';
        $length = strlen($needle);

        return substr($email, -$length) === $needle;
    }
}
