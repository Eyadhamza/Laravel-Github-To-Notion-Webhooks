<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubIssueHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestReviewHandler;
use PISpace\LaravelGithubToNotionWebhooks\Middleware\VerifyGithubSignature;
use PISpace\LaravelGithubToNotionWebhooks\Requests\GithubWebhook;


class GithubWebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(VerifyGithubSignature::class);
    }

    public function __invoke(Request $request): JsonResponse
    {
        $githubWebhook = GithubWebhook::build($request);

        Log::info('GithubWebhookController', [
            'request' => $request->all(),
        ]);

        match ($githubWebhook->getEntityType()) {
            GithubEventTypeEnum::ISSUE => GithubIssueHandler::run($githubWebhook),
            GithubEventTypeEnum::PULL_REQUEST => GithubPullRequestHandler::run($githubWebhook),
            GithubEventTypeEnum::PULL_REQUEST_REVIEW => GithubPullRequestReviewHandler::run($githubWebhook),
        };

        return response()->json([
            'status' => 'Success',
        ]);
    }
}
