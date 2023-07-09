<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

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
        // TODO: Implement update() method.
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
