<?php

declare(strict_types=1);

namespace App\FileObjects;

class User
{

    private int $id;
    private string $name;
    private string $username;
    private array $projects = [];

    public function __construct(array $data = [])
    {
        if ($data === []) {
            throw new \Exception('Cant load user without data');
        }

        $this->username = $data['username'];
        $this->id = $data['id'];
        $this->name = $data['name'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function addProject(string $projectName): void
    {
        $this->projects[] = $projectName;
    }

    public function getProjects(): array
    {
        return $this->projects;
    }

    public function toExportArray(): array
    {
        return [
            'name' => $this->getName() . '(' . $this->getUsername() . ')',
            'projects' => $this->getProjects(),
        ];
    }

}
