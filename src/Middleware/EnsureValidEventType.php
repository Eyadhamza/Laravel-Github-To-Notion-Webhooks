<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Exception\ExceptionHandler;

class EnsureValidEventType
{

    public function handle(Request $request, Closure $next)
    {
        $validEvents = config('github-webhooks.github.events');

        $githubEvent = Str::singular($request->header('X-Github-Event'));

        $event = collect($validEvents)
            ->first(fn($shouldTrigger, $event) => $shouldTrigger && $githubEvent === $event);

        if (!$event) {
            ExceptionHandler::badRequest('Invalid Event Type');
        }

        $request->merge(['event_type' => GithubEventTypeEnum::from($githubEvent)]);

        return $next($request);
    }
}
