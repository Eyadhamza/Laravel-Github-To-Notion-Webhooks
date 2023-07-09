<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Entities\GithubEntity;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\BaseGithubHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubIssueHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestReviewHandler;
use PISpace\LaravelGithubToNotionWebhooks\Middleware\VerifyGithubSignature;
use PISpace\LaravelGithubToNotionWebhooks\WebhookRequests\GithubWebhookRequest;

class GithubWebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyGithubSignature::class);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $githubWebhookRequest = GithubWebhookRequest::build($request);

        match ($githubWebhookRequest->getEntityType()) {
            GithubEventTypeEnum::ISSUE => GithubIssueHandler::run($githubWebhookRequest),
            GithubEventTypeEnum::PULL_REQUEST => GithubPullRequestHandler::run($githubWebhookRequest),
            GithubEventTypeEnum::PULL_REQUEST_REVIEW => GithubPullRequestReviewHandler::run($githubWebhookRequest),
        };

        return response()->json([
            'status' => 'Success',
        ]);
    }
}
