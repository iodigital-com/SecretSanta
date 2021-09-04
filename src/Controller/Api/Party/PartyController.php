<?php

declare(strict_types=1);

namespace App\Controller\Api\Party;

use App\Controller\Api\ApiHelper;
use App\Entity\Party;
use App\Service\PartyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PartyController extends AbstractController
{

    public function __construct(
        private NormalizerInterface $normalizer
    ) {}

    /**
     * @Route("/api/party/{listurl}", name="api_party_get", methods={"GET"})
     * @return Response
     */
    public function getParty(Party $party): Response
    {
        return new JsonResponse(
            $this->normalizer->normalize($party)
        );
    }

    /**
     * @Route("/api/party", name="api_party_create", methods={"POST"})
     */
    public function createParty(Request $request, PartyService $partyService): Response
    {
        // get Json object. If JsonResponse is returned
        $object = ApiHelper::getRequestBody($request);
        if ($object instanceof JsonResponse) return $object;

        $party = $partyService->createPartyFromObject($object, $request->getLocale());

        return new JsonResponse(
            [
                "statusCode" => "OK_PARTY_CREATED",
                "statusText" => "Party created"
            ],
            201
        );

    }

}
