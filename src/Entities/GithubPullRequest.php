<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use PISpace\LaravelGithubToNotionWebhooks\Enum\PullRequestActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubPullRequestInterface;
use PISpace\LaravelGithubToNotionWebhooks\Transformers\PullRequestTransformer;

class GithubPullRequest extends GithubContribution implements GitHubPullRequestInterface
{
    private PullRequestActionTypeEnum $action;
    protected array $reviewers;

    public function mapToNotion(): array
    {
        return PullRequestTransformer::transform($this);
    }

    public function setAction(string $action): self
    {
        $this->action = PullRequestActionTypeEnum::from($action);

        return $this;
    }

    public function setAttributes(array $data): self
    {
        parent::setAttributes($data);
        $this->setReviewers($data['requested_reviewers']);
        return $this;
    }

    public function getAction(): PullRequestActionTypeEnum
    {
        return $this->action;
    }

    public function setNotionDatabaseId(): GithubEntity
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.pull-requests');

        return $this;
    }

    public function isCreateAction(): bool
    {
        return $this->action === PullRequestActionTypeEnum::OPENED;
    }

    public function isUpdatedAction(): bool
    {
        return $this->action != PullRequestActionTypeEnum::OPENED;
    }

    public function isDeletedAction(): bool
    {
        return false;
    }

    private function setReviewers(array $data): void
    {
        $this->reviewers = collect($data)
            ->map(fn($reviewer) => GithubUser::fromResponse($reviewer)->getNotionUser())
            ->filter()
            ->flatten()
            ->all();
    }
}
