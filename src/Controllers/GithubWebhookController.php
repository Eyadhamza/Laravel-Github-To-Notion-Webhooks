<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use PISpace\LaravelGithubToNotionWebhooks\Enum\GithubEventTypeEnum;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubWebhookHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubIssueWebhookHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestWebhookHandler;
use PISpace\LaravelGithubToNotionWebhooks\Handlers\GithubPullRequestReviewWebhookHandler;
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
        Log::info('GithubWebhookController', [
            'request' => $request->all(),
        ]);

        $githubWebhook = GithubWebhook::build($request);


        GithubWebhookHandler::run($githubWebhook);

        return response()->json([
            'status' => 'Success',
        ]);
    }
}
