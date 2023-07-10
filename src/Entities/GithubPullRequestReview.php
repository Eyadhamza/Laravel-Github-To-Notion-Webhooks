<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;

class GithubPullRequestReview extends GithubEntity
{
    private IssueActionTypeEnum $action;
    private string $url;
    private string $title;
    private string $description;
    private string $state;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->notionDatabaseId = config('github-webhooks.notion.databases.issues');
        $this->setAttributes($data);
    }

    public function mapToNotion(): array
    {
        // TODO: Implement mapToNotion() method.
    }

    public function getAttributes(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
            'state' => $this->state,
            'author' => $this->sender->getName(),
            'repository' => $this->repository->getName(),
        ];
    }

    public function setAction(string $action): self
    {
        // TODO: Implement setAction() method.
    }

    protected function setAttributes(array $data): self
    {
        $this->url = $data['html_url'];
        $this->title = $data['title'];
        $this->description = $data['body'];
        $this->state = $data['state'];
        $this->action = IssueActionTypeEnum::from($data['action']);

        return $this;
    }

}