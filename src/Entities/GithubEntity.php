<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;


use Pi\Notion\Traits\Notionable;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

abstract class GithubEntity
{
    use Notionable;
    protected string $notionDatabaseId;
    protected string $id;
    protected GithubUser $sender;
    protected GithubRepository $repository;
    protected GithubEventTypeEnum $entityType;

    public function __construct(GithubWebhookRequest $request)
    {
        $data = $request->all();

        $this->entityType = $request->getEntityType();

        $this
            ->setAction($data['action'])
            ->setAttributes($data)
            ->setAuthor($data[$this->entityType->value]['user'])
            ->setRepository($data['repository']);
    }

    public static function make(GithubWebhookRequest $request): static
    {
        return new static($request);
    }

    abstract protected function setAttributes(array $data): self;
    abstract public function setAction(string $action): self;
    abstract public function getAction();

    public function getId(): string
    {
        return $this->id;
    }

    public function setAuthor(array $data): self
    {
        $this->sender = GithubUser::make($data);
        return $this;
    }

    private function setRepository(array $data): self
    {
        $this->repository = GithubRepository::make($data);
        return $this;
    }
}
