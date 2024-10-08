<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class EmailAddress
{
    /**
     * @Assert\NotBlank()
     *
     * @Assert\Email(
     *     mode="strict",
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private string $emailAddress;

    public function __construct(string $emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function __toString()
    {
        return $this->emailAddress;
    }
}
