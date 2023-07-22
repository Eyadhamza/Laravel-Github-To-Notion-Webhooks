<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;


use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\Resources\MissingValue;
use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Core\Models\NotionUser;
use Pi\Notion\Traits\Notionable;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;
use ReflectionObject;
use ReflectionProperty;

abstract class GithubEntity
{
    use Notionable, ConditionallyLoadsAttributes;

    protected string $notionDatabaseId;
    protected string $id;
    protected NotionUser|array $sender;
    protected string $repositoryName;
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
        $this->sender = $user->getNotionUser();

        return $this;
    }

    public function setRepository(GithubRepository $repository): self
    {
        $this->repositoryName = $repository->getName();

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

    abstract public function isCreateAction(): bool;

    abstract public function isUpdatedAction(): bool;

    abstract public function isDeletedAction(): bool;


    public function getAttributes()
    {
        $reflection = new ReflectionObject($this);

        return $this->filter(collect($reflection->getProperties())->mapWithKeys(function (ReflectionProperty $property) {
            if (empty($property->getValue($this))) {
                return new MissingValue();
            }
            return [
                $property->getName() => $property->getValue($this)
            ];
        })->all());
    }
}
