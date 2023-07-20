<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;

abstract class BaseGithubHandler
{
    protected GithubWebhook $request;
    protected GithubEntity $entity;

    public function __construct(GithubWebhook $request)
    {
        $this->request = $request;
        $this->entity = $request->getEntity();
    }

    public static function run(GithubWebhook $request): self
    {
        return (new static($request))->handle();
    }

    public function handle(): self
    {
        return match (true) {
            $this->isCreateAction() => $this->create(),
            $this->isUpdatedAction() => $this->update(),
            $this->isDeletedAction() => $this->delete(),
            default => $this
        };
    }

    abstract public function create(): self;

    abstract public function update(): self;

    abstract public function delete(): self;

    abstract protected function isCreateAction(): bool;

    abstract protected function isUpdatedAction(): bool;

    abstract protected function isDeletedAction(): bool;

}
