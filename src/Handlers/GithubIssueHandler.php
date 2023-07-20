<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Core\Models\NotionPage;
use Pi\Notion\Core\Properties\NotionText;
use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;

class GithubIssueHandler extends BaseGithubHandler
{
    public function create(): self
    {
        $this->entity->saveToNotion();

        return $this;
    }

    public function update(): self
    {
        $idColumnNameInNotion = $this->entity->mapToNotion()['id']->getName();

        $paginated = $this->entity->getNotionDatabase()
            ->setFilter(NotionText::make($idColumnNameInNotion)->equals($this->entity->getId()))
            ->query();

        $page = $paginated->getResults()->first();

        if (!$page) {
            return $this->create();
        }

        $this->entity->saveToNotion($page->getId());

        return $this;
    }

    public function delete(): self
    {
        $this->entity->deleteFromNotion($this->entity->getId());

        return $this;
    }

    protected function isCreateAction(): bool
    {
        return $this->entity->getAction() === IssueActionTypeEnum::OPENED;
    }

    protected function isUpdatedAction(): bool
    {
        return ! $this->isCreateAction() && ! $this->isDeletedAction();
    }

    protected function isDeletedAction(): bool
    {
        return $this->entity->getAction() === IssueActionTypeEnum::DELETED;
    }
}
