<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Handlers;

use Pi\Notion\Core\Properties\NotionText;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;

class GithubWebhookHandler
{
    protected GithubWebhook $request;
    protected GithubEntity $entity;

    public function __construct(GithubWebhook $request)
    {
        $this->request = $request;
        $this->entity = $request->getEntity();
    }

    public static function run(GithubWebhook $request): self
    {
        return (new static($request))->handle();
    }

    public function handle(): self
    {
        return match (true) {
            $this->entity->isCreateAction() => $this->create(),
            $this->entity->isUpdatedAction() => $this->update(),
            $this->entity->isDeletedAction() => $this->delete(),
            default => $this
        };
    }

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

}
