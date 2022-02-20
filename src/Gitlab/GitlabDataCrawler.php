<?php

declare(strict_types=1);

namespace App\Gitlab;

use App\FileObjects\User;
use Fig\Http\Message\StatusCodeInterface;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Nette\Utils\ArrayList;
use Nette\Utils\Json;
use Tracy\Debugger;

class GitlabDataCrawler
{
    public const PER_PAGE = 100;

    private Client $client;

    public function __construct(
        string $baseUri,
        private int $topLevelId,
        private string $accessToken
    ) {
        $this->client = new Client([
            'base_uri' => $baseUri,
        ]);
    }

    /**
     * @return array<int, User>
     */
    public function getDataFromApi(): array
    {
        $users = [];
        foreach ($this->prepareUsers() as $user) {
            $userObject = new User($user);

            $projects = $this->callEndpoint(sprintf(GitlabEndpoints::USER_PROJECTS, $userObject->getId()), false);
            if ($projects->count() === 0) {
                $users[] = $userObject;
                continue;
            }

            foreach ($projects as $project) {
                $userObject->addProject($project['name']);
            }

            $users[] = $userObject;
        }

        return $users;
    }

    /**
     * @return ArrayList
     * @throws \Nette\Utils\JsonException
     */
    private function prepareUsers(): ArrayList
    {
        return $this->callEndpoint(GitlabEndpoints::USERS, false);
    }

    private function callEndpoint(string $endpoint, bool $paginator = true): ArrayList
    {
        $result = [];
        $page = 1;
        while (true) {
            $response = $this
                ->client
                ->get(
                    $paginator ? GitlabEndpoints::createPaginatorToEndpoint($endpoint, $page) : $endpoint,
                    [
                        RequestOptions::HEADERS => [
                            'PRIVATE-TOKEN' => $this->accessToken,
                        ]
                    ]
                );

            if ($response->getStatusCode() !== StatusCodeInterface::STATUS_OK) {
                Debugger::log($response->getBody()->getContents(), 'gitlab_error');

                throw new \Exception('GitLab parsing failed');
            }

            try {
                $resultRow = Json::decode($response->getBody()->getContents(), Json::FORCE_ARRAY);

                if ($resultRow === []) {
                    break;
                }

                $result = array_merge($result, $resultRow);

                if ($resultRow < self::PER_PAGE || !$paginator) {
                    break;
                }
                $page++;

                sleep(5); //Gitlab have limits per api
            } catch (\JsonException) {
                Debugger::log($response->getBody()->getContents(), 'gitlab_json_error');

                throw new \Exception('GitLab parsing json');
            }
        }

        return ArrayList::from($result);
    }
}
