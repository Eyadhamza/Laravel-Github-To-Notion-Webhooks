<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use Pi\Notion\Core\Models\NotionDatabase;
use Pi\Notion\Core\Models\NotionPage;
use Pi\Notion\Core\Query\NotionFilter;
use PISpace\LaravelGithubToNotionWebhooks\Enum\IssueActionTypeEnum;

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

        $paginated = NotionDatabase::make($this->entity->getNotionDatabaseId())
            ->filter(NotionFilter::text($idColumnNameInNotion)->equals($this->entity->getId()))
            ->query();

        $pageId = $paginated->getResults()[0]->getId();

        $this->entity->saveToNotion($pageId);

        return $this;
    }

    public function delete(): self
    {
        // TODO: Implement delete() method.
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
