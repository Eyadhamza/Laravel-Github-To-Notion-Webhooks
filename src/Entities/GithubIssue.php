<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\IssueTransformer;

class GithubIssue extends GithubContribution
{
    public IssueActionTypeEnum $action;

    public function mapToNotion(): array
    {
        return IssueTransformer::transform($this);
    }

    public function setAction(string $action): self
    {
        $this->action = IssueActionTypeEnum::from($action);
        return $this;
    }

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.issues');

        return $this;
    }

    public function isCreateAction(): bool
    {
        return $this->action === IssueActionTypeEnum::OPENED;
    }

    public function isUpdatedAction(): bool
    {
        return ! in_array($this->action, [IssueActionTypeEnum::OPENED, IssueActionTypeEnum::DELETED]);
    }

    public function isDeletedAction(): bool
    {
        return $this->action === IssueActionTypeEnum::DELETED;
    }

    public function getAction(): IssueActionTypeEnum
    {
        return $this->action;
    }
}
