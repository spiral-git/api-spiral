<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    $exceptions->render(function (ValidationException $e, $request) {
        return response()->json([
            "Message" => "Error de validación",
            "IsSuccess" => false,
            "Data" => $e->errors()
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    });

    $exceptions->render(function (Throwable $e, $request) {

        return response()->json([
            "Message" => $e->getMessage(),
            "IsSuccess" => false,
            "Data" => null
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    });

})->create();
