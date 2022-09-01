<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $user = $this->userModel->getUserByEmail($credentials['email']);

        if(!$user->isUserActive())
            return response()->json(['error' => trans('auth.not_activated')], 423);

        if($token = Auth::attempt($credentials)) {
            $user->last_success_login = now();
            $user->save();
            return response()->json(['status' => 'success', 'user' => $user])->header('Authorization', $token);
        }

        $user->last_wrong_login = now();
        $user->save();

        return response()->json(['error' => trans('auth.failed')], 401);
    }

    public function logout() : JsonResponse
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out successfully'
        ]);
    }

    public function refresh() : JsonResponse
    {
        try {
            $token = $this->guard()->refresh();
            return response()->json(['status' => 'success'])->header('Authorization', $token);
        }
        catch(\Exception $e)
        {
            return response()->json(['error' => 'Can not refresh token'], 401);
        }
    }

    private function guard()
    {
        return Auth::guard();
    }
}
