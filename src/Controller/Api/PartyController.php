<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Party;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class PartyController extends AbstractController
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {}

    /**
     * @Route("/api/party/{listurl}", name="api_get_party", methods={"GET"})
     * @return Response
     */
    public function getParty(Party $party): Response
    {
        return new JsonResponse(
            $this->normalizer->normalize($party)
        );
    }
}