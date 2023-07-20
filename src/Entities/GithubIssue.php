<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Http\Resources\MissingValue;
use Illuminate\Support\Collection;
use Pi\Notion\Core\Builders\NotionBlockBuilder;
use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Core\Properties\NotionMultiSelect;
use Pi\Notion\Core\Properties\NotionPeople;
use Pi\Notion\Core\Properties\NotionSelect;
use Pi\Notion\Core\Properties\NotionText;
use Pi\Notion\Core\Properties\NotionTitle;
use Pi\Notion\Core\Properties\NotionUrl;
use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubEntityInterface;

class GithubIssue extends GithubEntity implements GithubEntityInterface
{
    use ConditionallyLoadsAttributes;
    private IssueActionTypeEnum $action;
    private string $url;
    private string $title;
    private string $description;
    private string $state;

    /** @var array<GithubUser> */
    private array $assignees;
    private Collection $labels;

    public function mapToNotion(): array
    {
        return [
            'id' => NotionText::make('Issue ID'),
            'title' => NotionTitle::make('Title'),
            'url' => NotionUrl::make('Url'),
            'description' => NotionText::make('Description'),
            'state' => NotionSelect::make('Status'),
            'author' => NotionPeople::make('Author')->setPeople($this->sender->getNotionUser()),
            'repository' => NotionText::make('Repository'),
            'assignees' => NotionPeople::make('Assignees')->setPeople($this->assignees),
            'labels' => NotionMultiSelect::make('Labels'),
        ];
    }

    public function getAttributes(): array
    {
        return $this->filter([
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
            'state' => $this->state,
            'repository' => $this->repository->getName(),
            'labels' => $this->when($this->labels->isNotEmpty(), $this->labels->all()),
        ]);
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

    public function setAttributes(array $data): self
    {
        $this->id = $data['id'];
        $this->url = $data['html_url'];
        $this->title = $data['title'];
        $this->description = $data['body'];
        $this->state = $data['state'];
        $this->assignees = collect($data['assignees'])->map(fn($assignee) => GithubUser::fromResponse($assignee)->getNotionUser())->flatten()->all();
        $this->setLabels($data['labels']);
        return $this;
    }

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.issues');

        return $this;
    }

    protected function setBlockBuilder(): void
    {
        $this->blockBuilder->paragraph($this->description);
    }

    private function setLabels(array $labels): self
    {
        $this->labels = collect($labels)->map(fn($label) => $label['name']);

        return $this;
    }
}
