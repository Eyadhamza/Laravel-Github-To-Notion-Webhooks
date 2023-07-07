<?php

namespace PISpace\LaravelGithubToNotionWebhooks\WebhookRequests;

use Illuminate\Http\Request;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;

class GithubWebhookRequest
{
    private GithubEventTypeEnum $eventType;
    public function __construct(
        private Request $request
    ) {
        $this->setEventType();
    }
    public static function build(Request $request): static
    {
        return (new static($request));
    }

    public function setEventType(): self
    {
        foreach (config('github-webhooks.events') as $event => $value) {
            if ($value && $this->request->has($event)) {
                $this->eventType = GithubEventTypeEnum::from($event);
                break;
            }
        }
        return $this;
    }

    public function getEventType(): GithubEventTypeEnum
    {
        return $this->eventType;
    }
}
