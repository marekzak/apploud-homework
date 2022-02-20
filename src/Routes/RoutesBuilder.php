<?php

declare(strict_types=1);

namespace App\Routes;

use App\Controllers\UsersController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

class RoutesBuilder
{
    public function __construct(
        private UsersController $usersController,
    ) {
    }

    public function build(App $app): void
    {
        $app->group('/users', function (RouteCollectorProxy $group) {
            $group->get('/', [$this->usersController, 'get']);
        });
    }
}
