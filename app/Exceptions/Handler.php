<?php

namespace App\Exceptions;

use App\Exceptions\Api\ActionException;
use App\Exceptions\Api\ApiException;
use App\Exceptions\Api\UnknownException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Exceptions\Api\NotFoundException;
use App\Exceptions\Api\NotOwnerException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        TokenMismatchException::class,
        ValidationException::class,
        ApiException::class,
        UnknownException::class,
        MethodNotAllowedHttpException::class,
        NotFoundException::class,
        NotOwnerException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        switch ($e) {
            case ($e instanceOf ActionException
                || $e instanceOf NotFoundException
                || $e instanceOf NotOwnerException
                || $e instanceOf UnknownException
            ):
                return $this->setResponse($e->getCode(), $e->getErrorDescription());

            case ($e instanceOf MethodNotAllowedHttpException):
                return $this->setResponse($e->getStatusCode(), $e->getMessage());

            default:
                break;
        }

        return parent::render($request, $e);
    }

    private function setResponse(int $httpCode, $description = [])
    {
        $httpCode = $httpCode !== 0 ? $httpCode : 500;
        $description = $description ? $description : translate('http_message.' . config('httpstatus.code.' . $httpCode));

        $response = [
            'message' => [
                'status' => false,
                'code' => $httpCode,
                'description' => [$description]
            ]
        ];

        return response()->json($response, $httpCode);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Illuminate\Auth\AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => [
                    'status' => false,
                    'code' => 401,
                    'description' => $exception->getMessage() ? [$exception->getMessage()] : [],
                ]
            ], 401);
        }

        return redirect()->guest('login');
    }
}
