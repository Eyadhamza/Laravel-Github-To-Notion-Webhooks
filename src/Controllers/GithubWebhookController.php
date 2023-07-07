<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\BaseGithubHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubIssueHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestReviewHandler;
use PISpace\LaravelGithubToNotionWebhooks\Middleware\VerifyGithubSignature;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

class GithubWebhookController extends Controller
{
    private GithubWebhookRequest $request;

    public function __construct()
    {
        $this->middleware(VerifyGithubSignature::class);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->request = GithubWebhookRequest::build($request);

        match ($this->request->getEventType()) {
            GithubEventTypeEnum::ISSUE => GithubIssueHandler::run($this->request),
            GithubEventTypeEnum::PULL_REQUEST => GithubPullRequestHandler::run($this->request),
            GithubEventTypeEnum::PULL_REQUEST_REVIEW => GithubPullRequestReviewHandler::run($this->request),
        };

        return response()->json([
            'status' => 'Success',
        ]);
    }
}
