<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Requests;

use Illuminate\Http\Request;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubIssue;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequest;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubPullRequestReview;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubRepository;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubUser;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Exception\ExceptionHandler;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubIssueInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubPullRequestInterface;
use PISpace\LaravelGithubToNotionWebhooks\Interfaces\GitHubPullRequestReviewInterface;

class GithubWebhook
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
        $validEvents = config('github-webhooks.github.events');

        foreach ($validEvents as $event => $value) {
            if ($value && $this->request->has($event)) {
                $this->eventType = GithubEventTypeEnum::from($event);
                return $this;
            }
        }

        ExceptionHandler::badRequest('Invalid event type');
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
