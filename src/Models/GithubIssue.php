<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Models;

use Pi\Notion\Core\NotionProperty;
use Pi\Notion\Traits\Notionable;

class GithubIssue
{
    use Notionable;
    protected string $notionDatabaseId;

    private string $url;
    private string $title;
    private string $description;

    public function __construct(array $data)
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.issues');
        $this->url = $data['html_url'];
        $this->title = $data['title'];
        $this->description = $data['body'];
    }

    public static function make(array $data): self
    {
        return new static($data);
    }

    public function mapToNotion(): array
    {
        return [
            'title' => NotionProperty::title(),
            'url' => NotionProperty::url(),
            'description' => NotionProperty::richText('Description'),
        ];
    }
    public function getAttributes(): array
    {
        return [
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
