<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	private User $userModel;

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function login(LoginRequest $request): JsonResponse
	{
		$credentials = $request->only('email', 'password');
		$user = $this->userModel->getUserByEmail($credentials['email']);

		if (!$user->isUserActive()) {
			return response()->json(['errors' => ['account' => trans('auth.not_activated')]], 423);
		}

		if ($token = Auth::attempt($credentials)) {
			$user->last_success_login = now();
			$user->save();
			return response()
				->json([
					'status' => 'success',
					'user' => [
						'id' => $user->id,
						'account_role' => $user->account_role,
						'email' => $user->email,
						'locale' => $user->locale,
					],
				])
				->header('Authorization', $token);
		}

		$user->last_wrong_login = now();
		$user->save();

		return response()->json(['errors' => ['account' => trans('auth.failed')]], 401);
	}

	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json([
			'status' => 'success',
			'msg' => 'Logged out successfully',
		]);
	}

	public function refresh(): JsonResponse
	{
		try {
			$token = $this->guard()->refresh();
			return response()
				->json(['status' => 'success'])
				->header('Authorization', $token);
		} catch (\Exception $e) {
			return response()->json(['errors' => ['account' => 'Can not refresh token']], 401);
		}
	}

	private function guard()
	{
		return Auth::guard();
	}
}
