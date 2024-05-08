<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler,
    Symfony\Component\Routing\Exception\RouteNotFoundException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Illuminate\Auth\AuthenticationException,
    Illuminate\Database\QueryException,
    Exception,
    Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (AuthenticationException $e) {
            return response()->json(
                [
                    'message' => "Authentication Invalid",
                    'results' => [],
                    'code' => 401,
                    'errors' => true,
                ],
                401
            );
        });

        $this->renderable(function (RouteNotFoundException $e) {
            return response()->json(
                [
                    'message' => "User Not Authenticated",
                    'results' => [],
                    'code' => 404,
                    'errors' => true,
                ],
                404
            );
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json(
                [
                    'message' => "Data not found. Please double check your data.",
                    'results' => [],
                    'code' => 404,
                    'errors' => true,
                ],
                404
            );
        });

        $this->renderable(function (QueryException $e) {
            return response()->json(
                [
                    'message' => "Some data does not exist. Please double check your data.",
                    'results' => [],
                    'code' => 404,
                    'errors' => true,
                ],
                404
            );
        });
    }
}
