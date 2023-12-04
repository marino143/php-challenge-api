<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Auth\StatefulGuard;
use Exception;

class PasswordRecoveryController extends Controller
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
    public function passwordRecovery(Request $request): JsonResponse
    {
        try {
            $user = User::where('email', '=', $request->get('email'))->first();

            if (null === $user) {
                return response()->json([
                    'message' => 'User not found',
                ], 404);
            }

            $userVerification = UserVerification::create([
                'user_id' => $user->__get('id'),
                'token' => md5(microtime() . $user->__get('email')),
            ]);

            $user->update([
                'is_verified' => false,
            ]);

            Mail::send('e-mail.password-recovery', ['user' => $user, 'userVerification' => $userVerification], function ($message) use ($request) {
                $message->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->sender(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $message->to($request->get('email'));
                $message->subject(env('MAIL_FROM_NAME') . ' - Password recovery');
            });

            return response()->json([
                'message' => 'Password recovery token sent',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function changePassword(Request $request): JsonResponse
    {
        try {
            $verification = UserVerification::where('token', '=', $request->get('token'))->first();

            if (null === $verification) {
                return response()->json([
                    'message' => 'Token has expired',
                ], 404);
            }

            if (null === $request->get('password')) {
                return response()->json([
                    'message' => 'Please enter new password',
                ], 400);
            }

            $user = User::where('id', '=', $verification->__get('user_id'))->first();

            $user->update([
                'password' => Hash::make($request->get('password')),
                'is_verified' => true,
            ]);

            $verification->delete();

            return response()->json([
                'message' => 'Password has been changed',
            ], 200);

        } catch (Exception $exception) {
            return response()->json([
                'message' => 'Something went wrong',
            ], 400);
        }
    }
}
