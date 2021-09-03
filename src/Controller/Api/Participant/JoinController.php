<?php

declare(strict_types=1);

namespace App\Controller\Api\Participant;

use App\Entity\Party;
use App\Model\Exception\InvalidJoinLink;
use App\Model\JoinLinkDetails;
use App\Repository\PartyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class JoinController extends AbstractController
{
    public function __construct(
        private NormalizerInterface $normalizer
    ) {}

    /**
     * @Route("/api/join/{joinUrl}", name="get_info_for_participants")
     */
    public function getInfoForParticipants(string $joinUrl, PartyRepository $partyRepository): Response
    {
        try {
            $details = $partyRepository->getDetailsForJoinUrl($joinUrl);

            return new JsonResponse(
                $this->normalizer->normalize($details)
            );
        } catch (InvalidJoinLink) {
            return new JsonResponse(
                [
                'statusCode' => 'NOT_FOUND',
                'statusText' => 'Party not found',
                ],
                404
            );
        }
    }
}