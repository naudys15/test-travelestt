<?php

namespace Travelestt\Exceptions;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Validation\ValidationException;
use Travelestt\Http\Traits\ApiResponse;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Responsable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof HttpException) {
            if (in_array($request->segment(1), Config::get('app.available_language'))) {
                return parent::render($request, $exception);
            } else {
                $code = $exception->getStatusCode();
                $message = Response::$statusTexts[$code];
                return $this->errorResponse($message, $code);
            }
        }
        if ($exception instanceof ModelNotFoundException) {
            $model = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("Does not exist any instance of {$model} with the given id", Response::HTTP_NOT_FOUND);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_FORBIDDEN);
        }
        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        }
        if ($exception instanceof ValidationException) {
            $errors = $exception->validator->errors()->getMessages();
            $decorateWord = [
                '',
                '_id'
            ];
            foreach ($errors as $key => $value) {
                foreach ($decorateWord as $word) {
                    if (str_contains($key, $word)) {
                        unset($errors[$key]);
                        $newKey = str_replace($word, '', $key);
                        $errors[$newKey] = $value;
                    }
                }
            }
            return $this->errorResponse($errors, Response::HTTP_BAD_REQUEST);
        }
        if (env('APP_DEBUG', true)) {
            return parent::render($request, $exception);
        }
        return $this->errorResponse("Unexpect error. Try later", Response::HTTP_INTERNAL_SERVER_ERROR);
        
    }
}
