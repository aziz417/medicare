<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Routing\Router;
use Illuminate\Http\JsonResponse;
use App\Http\Resources\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

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
        if (method_exists($exception, 'render') && $response = $exception->render($request)) {
            return Router::toResponse($request, $response);
        } elseif ($exception instanceof Responsable) {
            return $exception->toResponse($request);
        }

        $exception = $this->prepareException($exception);

        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        } elseif ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } elseif ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        return $this->json($request)
                    ? $this->prepareJsonResponse($request, $exception)
                    : $this->prepareResponse($request, $exception);
        // return parent::render($request, $exception);
    }

    /**
     * Check user can receive JSON
     * @param  Request $request 
     * @return boolean
     */
    public function json($request)
    {
        return $request->expectsJson() || $request->is('api/*');
    }

    /**
     * Get the HTTP status code
     *
     * @param  \Throwable  $exception
     * @return integer | code
     */
    public function getHttpCode(Throwable $exception)
    {
        $code = $exception->getCode();
        return $this->isHttpException($exception) ? 
            $exception->getStatusCode() : 
            ($code < 100 ? 400 : $code);
    }

    /**
     * Get the HTTP error message
     *
     * @param  \Throwable  $exception
     * @return string
     */
    public function getErrorMessage(Throwable $exception)
    {
        $message = $exception->getMessage();
        $code = $this->getHttpCode($exception);
        if( empty($message) ){
            switch ($code) {
                case 404:
                    $message = "404 - Not Found!";
                    break;
                default:
                    $message = "Something is wrong, Please try again!";
                    break;
            }
        }
        return $message;
    }
    
    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     * @see     \Illuminate\Foundation\Exceptions\Handler@unauthenticated
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->json($request)
                    ? response()->json([
                        'status' => false,
                        'code' => 401,
                        'message' => $this->getErrorMessage($exception)
                    ], 401)
                    : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        if ($exception->response) {
            return $exception->response;
        }

        return $this->json($request)
                    ? $this->invalidJson($request, $exception)
                    : $this->invalid($request, $exception);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status' => false,
            'code' => $exception->status,
            'message' => $this->getErrorMessage($exception),
            'errors' => $exception->errors(),
        ], $exception->status);
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Throwable  $exception
     * @return array
     */
    protected function convertExceptionToArray(Throwable $exception)
    {
        return [
            'status' => false,
            'code' => $this->getHttpCode($exception),
            'message' => $this->getErrorMessage($exception),
        ];
    }
    
    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $exception)
    {
        return new JsonResponse(
            $this->convertExceptionToArray($exception),
            $this->getHttpCode($exception),
            $this->isHttpException($exception) ? $exception->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
