<?php

namespace PISpace\LaravelGithubToNotionWebhooks\Exception;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ExceptionHandler extends Exception
{
    public function __construct(string $message, int $code, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message], $this->code);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function notFound(string $message): ExceptionHandler
    {
        throw new ExceptionHandler($message, Response::HTTP_NOT_FOUND);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function notFoundIf(bool $condition, string $message)
    {
        if ($condition) {
            self::notFound($message);
        }
    }

    /**
     * @throws ExceptionHandler
     */
    public static function badRequest(string $message)
    {
        throw new ExceptionHandler($message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function badRequestIf(bool $condition, string $message)
    {
        if ($condition) {
            self::badRequest($message);
        }
    }

    /**
     * @throws ExceptionHandler
     */
    public static function unprocessableEntity(string $message)
    {
        throw new ExceptionHandler($message, Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @throws ExceptionHandler
     */
    public static function unprocessableEntityIf(bool $condition, string $message)
    {
        if ($condition) {
            self::unprocessableEntity($message);
        }
    }
}
