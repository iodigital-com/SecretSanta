<?php

namespace Intracto\SecretSantaBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class EmailAddress
{
    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Email(
     *     strict=true,
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = true
     * )
     */
    private $emailAddress;

    public function __construct($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function __toString()
    {
        return $this->emailAddress;
    }
}
