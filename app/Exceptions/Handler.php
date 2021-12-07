<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Http\Response;

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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $response = [
                'meta' => [
                    'code' => 400,
                    'api_version' => '1.0',
                    'message' => 'Error',
                    'method' => $request->server('REQUEST_METHOD'),
                ],
                'errors' => [$exception->getMessage()],
                'data' => [
                    'message' => (string)$exception->getMessage(),
                    'items' => []
                ]
            ];
            if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $response['meta']['code'] = $exception->getStatusCode();
                return response()->json($response, $exception->getStatusCode());
            } else if ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                $response['meta']['code'] = Response::HTTP_NOT_FOUND;
                return response()->json($response, Response::HTTP_NOT_FOUND);
            } else if ($exception instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Illuminate\Session\TokenMismatchException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \Laravel\Passport\Exceptions\OAuthServerException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            } else if ($exception instanceof \League\OAuth2\Server\Exception\OAuthServerException) {
                $response['meta']['code'] = Response::HTTP_UNAUTHORIZED;
                return response()->json($response, Response::HTTP_UNAUTHORIZED);
            }
            if (!($exception instanceof \Illuminate\Validation\ValidationException)) {
                $response['meta']['code'] = 400;
                return response()->json($response, 400);
            }
            return parent::render($request, $exception);
        }
        return parent::render($request, $exception);
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
        if ($exception->guards()[0] === 'web') {
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        } else {
            $response = [
                'meta' => [
                    'code' => Response::HTTP_UNAUTHORIZED,
                    'api_version' => '1.0',
                    'message' => 'Error',
                    'method' => $request->server('REQUEST_METHOD'),
                ],
                'errors' => [$exception->getMessage()],
                'data' => [
                    'message' => (string)$exception->getMessage(),
                    'items' => []
                ]
            ];
            return response()->json($response, Response::HTTP_UNAUTHORIZED);
        }
    }
}
