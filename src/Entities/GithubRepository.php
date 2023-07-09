<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubRepository extends GithubEntity
{

    private string $name;

    public function mapToNotion(): array
    {
        // TODO: Implement mapToNotion() method.
    }

    public function getAttributes(): array
    {
        // TODO: Implement getAttributes() method.
    }

    public function setAttributes(array $data): self
    {
        $this->name = $data['repository']['name'];

        return $this;
    }

    public function setAction(string $action): self
    {
        return $this;
    }

    public function setNotionDatabaseId(): self
    {
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
}
