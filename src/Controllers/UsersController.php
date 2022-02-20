<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Gitlab\GitlabExporter;
use App\Models\StatusMessage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UsersController extends BaseController
{
    public function __construct(private GitlabExporter $gitlabExporter)
    {
    }

    public function get(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $this->sendJsonResponse(
            $response,
            new StatusMessage(
                $this->gitlabExporter->prepareResponse()
            )
        );
    }
}
