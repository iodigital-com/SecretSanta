<?php

namespace App\Model;

use Symfony\Component\Validator\Constraints as Assert;

class ContactSubmission
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @Assert\Email(
     *     mode="strict",
     *     message = "The email '{{ value }}' is not a valid email."
     * )
     */
    private string $email;

    /**
     * @Assert\NotBlank()
     */
    private string $message;

    /**
     * @Assert\NotBlank()
     */
    private string $recaptchaToken;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email)
    {
        $this->email = $email;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    public function getRecaptchaToken(): string
    {
        return $this->recaptchaToken;
    }

    public function setRecaptchaToken(string $recaptchaToken)
    {
        $this->recaptchaToken = $recaptchaToken;
    }
}
