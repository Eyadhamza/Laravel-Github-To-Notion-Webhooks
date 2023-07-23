<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use PISpace\LaravelGithubToNotionWebhooks\Transformers\ContributionTransformer;

abstract class GithubContribution extends GithubEntity
{
    protected string $url;
    protected string $title;
    protected ?string $description;
    protected string $state;

    /** @var array<GithubUser> */
    protected array $assignees;
    protected array $labels;
    protected string $status;

    public function setAttributes(array $data): self
    {
        $this->id = $data['id'];
        $this->url = $data['html_url'];
        $this->title = $data['title'];
        $this->description = $data['body'];
        $this->state = $data['state'];
        $this->status = $this->getAction()->value;
        $this->assignees = collect($data['assignees'])->map(fn($assignee) => GithubUser::fromResponse($assignee)->getNotionUser())->flatten()->toArray();
        $this->setLabels($data['labels']);

        return $this;
    }

    public function mapToNotion(): array
    {
        return ContributionTransformer::transform($this);
    }

    protected function setLabels(array $labels): self
    {
        $this->labels = collect($labels)->map(fn($label) => ['name' => $label['name']])->toArray();

        return $this;
    }

    protected function setBlockBuilder(): void
    {
        if (!$this->description) return;

        $this->blockBuilder->paragraph($this->description);
    }

}
