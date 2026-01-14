<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        /**
         * @param string|null $message The message to send in the response (default is 'Success')
         * @param mixed $data The data to include in the response (default is [])
         * @param int $status The HTTP status code (default is 200)
         * @return \Illuminate\Http\JsonResponse JSON response with 'success' as true
         */
        Response::macro('apiSuccess', function ($message = 'Success', $data = [], $status = 200) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data,
            ], $status);
        });

        /**
         * @param string $message The error message to send in the response (default is 'Error')
         * @param mixed|null $errors The validation or error details (default is [])
         * @param int $status The HTTP status code (default is 400)
         * @return \Illuminate\Http\JsonResponse JSON response with 'success' as false
         */
        Response::macro('apiError', function ($message = 'Error', $errors = [], $status = 400) {
            return response()->json([
                'success' => false,
                'message' => $message,
                'errors' => $errors,
            ], $status);
        });
    }
}
