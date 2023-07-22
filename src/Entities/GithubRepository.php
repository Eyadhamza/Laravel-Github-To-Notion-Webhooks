<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

class GithubRepository
{

    private string $name;

    public static function fromResponse(array $data): self
    {
        return (new static())
            ->setAttributes($data);
    }

    public function getName(): string
    {
        return $this->name;
    }

    private function setAttributes(array $data): self
    {
        $this->name = $data['name'];

        return $this;
    }

}
