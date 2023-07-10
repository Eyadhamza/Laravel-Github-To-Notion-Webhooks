<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use Pi\Notion\Core\BlockContent\NotionRichText;
use Pi\Notion\Core\Models\NotionUser;
use Pi\Notion\Core\NotionProperty\NotionPeople;
use Pi\Notion\Core\NotionProperty\NotionSelect;
use Pi\Notion\Core\NotionProperty\NotionText;
use Pi\Notion\Core\NotionProperty\NotionTitle;
use Pi\Notion\Core\NotionProperty\NotionUrl;
use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

class GithubIssue extends GithubEntity
{
    protected string $notionDatabaseId;
    private IssueActionTypeEnum $action;
    private string $url;
    private string $title;
    private string $description;
    private string $state;

    public function __construct(GithubWebhookRequest $request)
    {
        parent::__construct($request);

        $this->setNotionDatabaseId();
    }

    public function mapToNotion(): array
    {
        return [
            'title' => NotionTitle::make('Title'),
            'url' => NotionUrl::make('Url'),
            'description' => NotionText::make('Description'),
            'state' => NotionSelect::make('Status'),
            'author' => NotionPeople::make('Author')
                ->setPeople([
                    new NotionUser('2c4d6a4a-12fe-4ce8-a7e4-e3019cc4765f')
                ]),
            'repository' => NotionText::make('Repository'),
        ];
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
        $this->action = IssueActionTypeEnum::from($action);
        return $this;
    }

    public function getAction(): IssueActionTypeEnum
    {
        return $this->action;
    }

    protected function setAttributes(array $data): self
    {
        $this->url = $data['issue']['html_url'];
        $this->title = $data['issue']['title'];
        $this->description = $data['issue']['body'];
        $this->state = $data['issue']['state'];
        $this->action = IssueActionTypeEnum::from($data['action']);

        return $this;
    }

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.issues');
        return $this;
    }
}
