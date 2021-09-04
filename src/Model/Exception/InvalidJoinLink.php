<?php

declare(strict_types=1);

namespace App\Model\Exception;

final class InvalidJoinLink extends \Exception
{
    public static function forUrl(string $joinUrl): self
    {
        return new self("Could not join a party via url {$joinUrl}.");
    }
}