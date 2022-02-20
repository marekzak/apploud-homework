<?php

declare(strict_types=1);

namespace App\Controllers;

use JsonSerializable;
use Psr\Http\Message\ResponseInterface;

abstract class BaseController
{
    /**
     * @param ResponseInterface $response
     * @param array<string, mixed>|JsonSerializable $payload
     * @param int $statusCode
     * @return ResponseInterface
     */
    protected function sendJsonResponse(
        ResponseInterface $response,
        array|JsonSerializable $payload,
        int $statusCode = 200
    ): ResponseInterface {

        $json = json_encode($payload);

        if ($json) {
            $response
                ->getBody()
                ->write($json);
        }

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($statusCode);
    }
}
