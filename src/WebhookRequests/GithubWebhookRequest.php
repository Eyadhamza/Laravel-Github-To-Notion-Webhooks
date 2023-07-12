<?php

namespace PISpace\LaravelGithubToNotionWebhooks\WebhookRequests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequestReview;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;

class GithubWebhookRequest
{
    private GithubEventTypeEnum $eventType;
    private GithubEntity $entity;

    public function __construct(
        private readonly Request $request
    )
    {
        $this->setEventType()->setEntity();
    }

    public static function build(Request $request): static
    {
        return (new static($request));
    }

    public function setEventType(): self
    {
        foreach (config('github-webhooks.github.events') as $event => $value) {
            if ($value && $this->request->has($event)) {
                $this->eventType = GithubEventTypeEnum::from($event);
                break;
            }
        }
        return $this;
    }

    public function getEntityType(): GithubEventTypeEnum
    {
        return $this->eventType;
    }
    private function setEntity(): self
    {
        $this->entity = match ($this->eventType) {
            GithubEventTypeEnum::ISSUE => GithubIssue::make($this),
            GithubEventTypeEnum::PULL_REQUEST => GithubPullRequest::make($this),
            GithubEventTypeEnum::PULL_REQUEST_REVIEW => GithubPullRequestReview::make($this),
        };

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

}
