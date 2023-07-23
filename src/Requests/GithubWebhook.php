<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequestReview;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubRepository;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubUser;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Exception\ExceptionHandler;

class GithubWebhook
{
    private GithubEventTypeEnum $eventType;
    private GithubEntity $entity;

    public function __construct(
        private readonly Request $request
    )
    {
        $this->eventType = $this->request->get('event_type');
        $this->setEntity();
    }

    public static function build(Request $request): static
    {
        return (new static($request));
    }


    public function getEntityType(): GithubEventTypeEnum
    {
        return $this->eventType;
    }
    private function setEntity(): self
    {
        $this->entity = match ($this->eventType) {
            GithubEventTypeEnum::ISSUE => GithubIssue::fromRequest($this),
            GithubEventTypeEnum::PULL_REQUEST => GitHubPullRequest::fromRequest($this),
            GithubEventTypeEnum::PULL_REQUEST_REVIEW => GitHubPullRequestReview::fromRequest($this),
        };

        $this->entity
            ->setSender(GithubUser::fromResponse($this->all()['sender']))
            ->setRepository(GithubRepository::fromResponse($this->all()['repository']));

        return $this;
    }

    public function getEntity(): GithubEntity
    {
        return $this->entity;
    }

    public function all(): array
    {
        return $this->request->all();
    }

    public function getAction()
    {
        return $this->request->get('action');
    }

    public function getId(): string
    {
        return $this->request->get($this->eventType->value)['id'];
    }

}
