<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ActionResponse
{
    private static function output(bool $success, mixed $response, int $code = 200): JsonResponse
    {
        return new JsonResponse(['success' => $success, 'result' => $response], $code);
    }

    public static function ok(mixed $response): JsonResponse
    {
        return self::output(true, $response, ResponseAlias::HTTP_OK);
    }

    public static function created(mixed $response): JsonResponse
    {
        return self::output(true, $response, ResponseAlias::HTTP_CREATED);
    }

    public static function badRequest(mixed $response): JsonResponse
    {
        return self::output(false, $response, ResponseAlias::HTTP_BAD_REQUEST);
    }

    public static function unAuthorized(mixed $response): JsonResponse
    {
        return self::output(false, $response, ResponseAlias::HTTP_UNAUTHORIZED);
    }

    public static function accessDenied(mixed $response): JsonResponse
    {
        return self::output(false, $response, ResponseAlias::HTTP_FORBIDDEN);
    }

    public static function notFound(mixed $response): JsonResponse
    {
        return self::output(false, $response, ResponseAlias::HTTP_NOT_FOUND);
    }

    public static function error(mixed $response): JsonResponse
    {
        return self::output(false, $response, ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
    }
}
