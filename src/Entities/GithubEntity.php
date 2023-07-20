<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;


use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Traits\Notionable;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubEntityInterface;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;

abstract class GithubEntity implements GithubEntityInterface
{
    use Notionable;
    protected string $notionDatabaseId;
    protected string $id;
    protected GithubUser $sender;
    protected GithubRepository $repository;
    protected GithubEventTypeEnum $entityType;
    protected NotionDatabase $notionDatabase;

    public function __construct(string $id = null)
    {
        $this->setNotionDatabaseId();
        $this->notionDatabase = NotionDatabase::make($this->notionDatabaseId);
    }

    public static function make(string $id): static
    {
        return new static($id);
    }
    public static function fromRequest(GithubWebhook $request): static
    {
        return (new static($request->getId()))
            ->setEntityType($request->getEntityType())
            ->setAction($request->getAction())
            ->setAttributes($request->all()[$request->getEntityType()->value]);
    }

    public static function fromResponse(array $data): static
    {
        return (new static($data['id']))
            ->setAttributes($data);
    }

    abstract public function setAttributes(array $data): self;
    abstract public function setAction(string $action): self;
    abstract public function getAction();

    public function getId(): string
    {
        return $this->id;
    }

    public function setSender(GithubUser $user): self
    {
        $this->sender = $user;

        return $this;
    }

    public function setRepository(GithubRepository $repository): self
    {
        $this->repository = $repository;

        return $this;
    }
    abstract public function setNotionDatabaseId(): self;

    private function setEntityType(GithubEventTypeEnum $entityType): static
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getNotionDatabase(): NotionDatabase
    {
        return $this->notionDatabase;
    }


}
