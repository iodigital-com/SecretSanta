<?php

declare(strict_types=1);

namespace App\Normalizer;

use App\Entity\Party;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PartyNormalizer implements NormalizerInterface
{
    public function normalize($object, $format = null, array $context = [])
    {
        if (!$object instanceof Party) {
            throw new \InvalidArgumentException('Party expected');
        }

        return [
            'listUrl' => $object->getListurl(),
            'wishlistUrl' => $object->getWishlistsurl(),
            'message' => $object->getMessage(),
            'creationDate' => $object->getCreationDate()->format('Y-m-d H:i:s'),
            'sentDate' => $object->getSentdate()?->format('Y-m-d H:i:s'),
            'eventDate' => $object->getEventdate()->format('Y-m-d H:i:s'),
            'amount' => $object->getAmount(),
            // we could normalize participants as well, if this turns out to be useful
            'locale' => $object->getLocale(),
            'location' => $object->getLocation(),
            'joinUrl' => $object->getJoinurl(),
            'joinMode' => $object->getJoinmode(),
            // I don't think we should disclose 'createdFromIp' to the frontend
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return ($data instanceof Party);
    }
}