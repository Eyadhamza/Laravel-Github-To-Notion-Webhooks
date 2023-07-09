<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;


use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

abstract class GithubEntity
{
    protected GithubSender $sender;
    protected GithubRepository $repository;
    protected GithubEventTypeEnum $entityType;
    private GithubWebhookRequest $request;

    public function __construct(GithubWebhookRequest $request)
    {
        $this->request = $request;

        $data = $request->all();

        $this->entityType = $request->getEntityType();

        $this
            ->setAction($data['action'])
            ->setAttributes($data);
    }

    public static function make(GithubWebhookRequest $request): self
    {
        return new static($request);
    }

    abstract protected function setAttributes(array $data): self;
    abstract public function setAction(string $action): self;
    abstract public function getAction();

    public function setAuthor(): self
    {
        $this->sender = GithubSender::make($this->request);

        return $this;
    }

    public function setRepository(): self
    {
        $this->repository = GithubRepository::make($this->request);

        return $this;
    }
}
