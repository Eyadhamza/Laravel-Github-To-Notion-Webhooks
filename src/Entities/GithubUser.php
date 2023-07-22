<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Entities;

use Pi\Notion\Core\Models\NotionPage;
use Pi\Notion\Core\Models\NotionUser;
use Pi\Notion\Core\Properties\BaseNotionProperty;
use Pi\Notion\Core\Properties\NotionText;
use Pi\Notion\Core\Properties\NotionTitle;

class GithubUser extends GithubEntity
{
    private string $name;
    private string $notionId;

    public function mapToNotion(): array
    {
        return [
            'id' => NotionText::make('GitHub ID'),
            'name' => NotionText::make('Name'),
            'notion_id' => NotionText::make('Notion ID'),
        ];
    }
    public function getAttributes(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function setAttributes(array $data): self
    {
        $this->id = $data['id'];
        $this->name = $data['login'];

        return $this;
    }

    public function setAction(string $action): self
    {
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setNotionDatabaseId(): self
    {
        $this->notionDatabaseId = config('github-webhooks.notion.databases.users');
        return $this;
    }

    public function getAction()
    {
        return null;
    }

    public function getNotionUser(): NotionUser|array|null
    {
        /** @var NotionPage $notionPage */
        $notionPage = $this->notionDatabase
            ->setFilter(
                NotionTitle::make('GitHub Username')->equals($this->name)
            )
            ->query()
            ->getResults()
            ->first();

        if (!$notionPage) {
            return null;
        }
        /** @var BaseNotionProperty $peopleProperty */
        $peopleProperty = $notionPage->getProperties()->first();

        return $peopleProperty->getValue();

    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function isCreateAction(): bool
    {
        return true;
    }

    public function isUpdatedAction(): bool
    {
        return false;
    }

    public function isDeletedAction(): bool
    {
        return false;
    }
}
