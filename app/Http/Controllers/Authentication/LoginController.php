<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\StatefulGuard;
use Exception;

class LoginController extends Controller
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
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        try {
            if (false === Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Please check your credentials',
                ], 401);
            }

            $user = User::where('email', '=', $request->input('email'))->first();

            if (false === $user->__get('is_verified')) {
                return response()->json([
                    'message' => 'Please verify your account',
                ], 401);
            }

            return response()->json([
                'authentication' => [
                    'token' => $user->createToken($user, [$user->__get('role')])->plainTextToken,
                ],
                'user' => $user,
                'message' => 'Welcome',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }
}
