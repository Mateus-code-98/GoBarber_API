<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
        if ($exception instanceof UnauthorizedHttpException){
            $preException = $exception->getPrevious();
            if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json([
                    'success' => false,
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'TOKEN_EXPIRED'
                ], Response::HTTP_UNAUTHORIZED);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'TOKEN_INVALID'
                ], Response::HTTP_UNAUTHORIZED);
            } else if ($preException instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                 return response()->json([
                    'success' => false,
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'TOKEN_BLACKLISTED'
                ], Response::HTTP_UNAUTHORIZED);
           }
            if ($exception->getMessage() === 'Token not provided') {
                return response()->json([
                    'success' => false,
                    'status' => Response::HTTP_UNAUTHORIZED,
                    'message' => 'Token not provided'
                ], Response::HTTP_UNAUTHORIZED);
            }
        }
        return parent::render($request, $exception);
    }
}
