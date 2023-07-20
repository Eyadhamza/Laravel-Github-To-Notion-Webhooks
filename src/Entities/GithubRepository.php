<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubRepository extends GithubEntity
{

    private string $name;


    public function setAttributes(array $data): self
    {
        $this->name = $data['name'];

        return $this;
    }

    public function getAction()
    {
        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setAction(string $action): self
    {
        return $this;
    }

    public function mapToNotion(): array
    {
        // TODO: Implement mapToNotion() method.
    }

    public function getAttributes()
    {
        // TODO: Implement getAttributes() method.
    }

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.repositories');
        return $this;
    }
}
