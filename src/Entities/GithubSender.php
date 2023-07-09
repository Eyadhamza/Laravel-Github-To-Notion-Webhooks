<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubSender extends GithubEntity
{
    private string $name;

    public function mapToNotion(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getAttributes(): array
    {
        return [
            'name' => $this->name,
        ];
    }

    public function setAttributes(array $data): GithubEntity
    {
        $data = $data[$this->entityType->value]['user'];

        $this->name = $data['login'];

        return $this;
    }

    public function setAction(string $action): self
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setNotionDatabaseId(): GithubEntity
    {
        return $this;
    }

    public function getAction()
    {
        return null;
    }
}
