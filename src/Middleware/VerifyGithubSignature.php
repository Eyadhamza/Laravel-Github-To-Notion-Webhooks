<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Middleware;

use Closure;
use Illuminate\Http\Request;
use PISpace\LaravelGithubToNotionWebhooks\Exception\ExceptionHandler;

class VerifyGithubSignature
{

    public function handle(Request $request, Closure $next)
    {
        $payloadBody = $request->getContent();

        $signature = 'sha256=' . hash_hmac('sha256', $payloadBody, config('github-webhooks.github.secret'));

        if (!hash_equals($signature, $request->header('X-Hub-Signature-256'))) {
            ExceptionHandler::badRequest('Invalid Signature');
        }

        return $next($request);
    }
}
