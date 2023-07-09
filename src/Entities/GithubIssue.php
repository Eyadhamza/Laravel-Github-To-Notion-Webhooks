<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use Pi\Notion\Core\NotionProperty;
use Pi\Notion\Traits\Notionable;
use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

class GithubIssue extends GithubEntity
{

    use Notionable;
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
            'title' => NotionProperty::title(),
            'url' => NotionProperty::url(),
            'description' => NotionProperty::richText('Description'),
            'state' => NotionProperty::richText('Status'),
            'author' => NotionProperty::richText('Author'),
            'repository' => NotionProperty::richText('Repository'),
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
