<?php

namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{

    public static function getRequestBody(Request $request): object
    {
        $object = json_decode($request->getContent());
        if ($object !== null) {
            return $object;
        } else {
            return new JsonResponse(
                [
                    "statusCode" => "ERR_INVALID_REQUEST",
                    "statusText" => "Invalid request"
                ],
                400
            );
        }
    }

}