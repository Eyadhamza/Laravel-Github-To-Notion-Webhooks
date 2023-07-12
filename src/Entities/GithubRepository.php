<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubRepository
{

    private string $name;
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->setAttributes();
    }

    public static function make(array $data): static
    {
        return new static($data);
    }

    public function setAttributes(): self
    {
        $this->name = $this->data['name'];

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
