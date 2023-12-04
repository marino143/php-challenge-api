<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\StatefulGuard;
use Exception;

class LogoutController extends Controller
{
    public function __construct()
    {
        Config::set('jwt.user', User::class);
        Config::set('auth.providers', ['users' => [
            'driver' => 'eloquent',
            'model' => User::class,
        ]]);
    }

    /**
     * @return StatefulGuard
     */
    public function guard(): StatefulGuard
    {
        return Auth::guard();
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            $user = Auth::guard('sanctum')->user();

            if (null === $user) {
                return response()->json([
                    'message' => 'You are logged out',
                ], 401);
            }

            $user->tokens()->delete();

            return response()->json([
                'message' => 'Goodbye',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }
}
