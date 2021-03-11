<?php

namespace App\Exceptions;

use Throwable;
use App\Exceptions\BadRoleException;
use App\Exceptions\NotFoundException;
use App\Exceptions\PrivilegeException;
use App\Exceptions\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    private function response($message, $status)
    {
        return response()->json(["message" => $message], $status);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof BadRoleException || $e instanceof PrivilegeException) {
            return $this->response($e->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
        if ($e instanceof NotFoundException) {
            return $this->response($e->getMessage(), Response::HTTP_NOT_FOUND);
        }
        if ($e instanceof NotFoundHttpException) {
            return $this->response("Ressource non disponible", Response::HTTP_NOT_FOUND);
        }
        if ($e instanceof BadRequestException) {
            return $this->response($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
        return parent::render($request, $e);
    }
}
