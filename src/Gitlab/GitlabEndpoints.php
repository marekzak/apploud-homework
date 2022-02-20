<?php

declare(strict_types=1);

namespace App\Gitlab;

abstract class GitlabEndpoints
{

    public const USERS = '/api/v4/users';

    public const USER_PROJECTS = '/api/v4/users/%s/projects';

    public static function createPaginatorToEndpoint(string $endpoint, int $page): string
    {
        return $endpoint . '?per_page=' . GitlabDataCrawler::PER_PAGE . '&page=' . $page;
    }

}
