<?php

declare(strict_types=1);

namespace App\Controller\Api\Party;

use App\Controller\Api\ApiHelper;
use App\Service\PartyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Party;
use Symfony\Component\Routing\Annotation\Route;

class PartyController extends AbstractController
{
    /**
     * @Route("/api/party", name="api_party_create", methods={"POST"})
     */
    public function createAction(Request $request, PartyService $partyService)
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
