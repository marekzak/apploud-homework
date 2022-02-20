<?php

declare(strict_types=1);

namespace App\Gitlab;

class GitlabExporter
{
    public function __construct(private GitlabDataCrawler $gitlabDataCrawler)
    {
    }

    public function prepareResponse(): array
    {
        $users = $this->gitlabDataCrawler->getDataFromApi();

        $result = [];

        foreach ($users as $user) {
            $result[] = $user->toExportArray();
        }

        return $result;
    }

}
