<?php

use App\Http\Responses\ActionResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (ValidationException $exception) {
            Log::channel('stack')->error($exception->getMessage());
            return ActionResponse::badRequest($exception->getMessage());
        });
        $exceptions->render(function (AuthenticationException | UnauthorizedException $exception) {
            Log::channel('stack')->error($exception->getMessage());
            return ActionResponse::unAuthorized('Unauthorized');
        });
        $exceptions->render(function (AccessDeniedHttpException $exception) {
            Log::channel('stack')->error($exception->getMessage());
            return ActionResponse::accessDenied('Access denied');
        });
        $exceptions->render(function (NotFoundResourceException $exception) {
            Log::channel('stack')->error($exception->getMessage());
            return ActionResponse::notFound($exception->getMessage());
        });
        $exceptions->render(function (NotFoundHttpException $exception) {
            Log::channel('stack')->error($exception->getMessage());
            return ActionResponse::notFound('Requested service not found');
        });
//       $exceptions->render(function (Exception $exception) {
//           Log::channel('slack')->error($exception->getMessage(),[
//               'file' => $exception->getFile(),
//               'Line' => $exception->getLine(),
//               'code' => $exception->getCode(),
//           ]);
//
//           Log::channel('stack')->error($exception->getMessage());
//           return ActionResponse::error('An error occurred, please try again');
//       });
    })->create();
