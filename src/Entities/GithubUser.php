<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubUser
{
    private string $id;
    private string $name;
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->setAttributes();
    }
    public static function make(array $data): self
    {
        return new static($data);
    }
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function setAttributes(): self
    {
        $this->name = $this->data['login'];

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

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.users');
        return $this;
    }

    public function getAction()
    {
        return null;
    }
}
