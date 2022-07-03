<?php

namespace App\Structure\Exceptions;

use Error;
use Exception;
use GuzzleHttp\Exception\RequestException;

use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Auth\AuthenticationException;

use Illuminate\Http\Exceptions\PostTooLargeException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

use Illuminate\Session\TokenMismatchException;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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
        if ( !config('app.debug') ) {
			$this->renderable(function (MethodNotAllowedHttpException $e, $request) {
                return response()->json([
                    'type' => 'error',
                    'message' => __('The specified method for the request is invalid.')
                ], 405);
			});
			$this->renderable(function (NotFoundHttpException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('The specified URL cannot be found.')
                ], 404);
			});
			$this->renderable(function (AuthenticationException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Unauthenticated. Your session or token has been expired. Please login again.')
                ], 401);
			});
			$this->renderable(function (AccessDeniedHttpException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Unauthorized. This action is unauthorized.')
                ], 401);
			});
			$this->renderable(function (TokenMismatchException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('TokenMismatchException! Your CSRF token has been expired. Request a new token and try again.')
                ], 419);
			});
			$this->renderable(function (QueryException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Internal server error. There was a problem performing the requested action on the database. Try later.')
                ], 500);
			});
			$this->renderable(function (ModelNotFoundException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('The requested information does not exist in our database.')
                ], 404);
			});
			$this->renderable(function (PostTooLargeException $e, $request) {
				//convertUploadedFileSizeToHumanReadable function is an helper (App\Structure\Support\helpers.php)
                return response()->json([
                    'type' => 'error',
					__(
						'Size of attached file should be less :size',
						['size' => convertUploadedFileSizeToHumanReadable( ini_get('upload_max_filesize') )]
					)
                ], 405);
			});
			$this->renderable(function (ThrottleRequestsException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Throttle requests exception too many attempts. Wait some minutes and try again.')
                ], 429);
			});
			$this->renderable(function (RequestException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Internal server error. External API call failed.')
                ], 500);
			});
			$this->renderable(function (HttpException $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => $e->getMessage(), $e->getCode()
                ]);
			});
			$this->renderable(function (Error $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => __('Internal server error.')
                ], 500);
			});
			$this->renderable(function (Exception $e, $request) {
                return response()->json([
                    'type' => 'error',
				    'message' => $e->getMessage(), $e->getCode()
                ]);
			});
		}
        $this->reportable(function (Throwable $e) {});
    }
}
