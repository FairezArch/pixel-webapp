<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function isAPI(): bool
    {
        return Request::is('api/*');
    }

    public function getMinimumLevel(): int
    {
        return config('permission.app.min_level.' . auth()->user()->getRoleNames()[0]);
    }

    public static function success(mixed $data = [], array $additional = [])
    {
        return response()->json([
            'status' => true,
            'data' => $data,
        ] + $additional);
    }
    public static function fail(?string $message = '', array $additional = [], int $statusCode = 400)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ] + $additional)->setStatusCode($statusCode);
    }
}
