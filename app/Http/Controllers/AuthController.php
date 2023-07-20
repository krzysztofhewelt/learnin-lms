<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
	use AuthenticatesUsers, ThrottlesLogins;

	private User $userModel;

	protected int $maxAttempts = 3;
	protected int $decayMinutes = 1;

	public function __construct()
	{
		$this->userModel = new User();
	}

	public function login(LoginRequest $request): JsonResponse
	{
		if ($this->hasTooManyLoginAttempts($request)) {
			return response()->json(
				['errors' => ['account' => trans('auth.throttle')]],
				Response::HTTP_TOO_MANY_REQUESTS,
			);
		}

		$credentials = $request->only('email', 'password');
		$user = $this->userModel->getUserByEmail($credentials['email']);

		if (!$user->isUserActive()) {
			return response()->json(
				['errors' => ['account' => trans('auth.not_activated')]],
				Response::HTTP_UNAUTHORIZED,
			);
		}

		if ($token = Auth::attempt($credentials)) {
			$user->last_success_login = now();
			$user->locale = App::getLocale();
			$user->save();
			$this->clearLoginAttempts($request);

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

		$this->incrementLoginAttempts($request);

		return response()->json(
			['errors' => ['account' => trans('auth.failed')]],
			Response::HTTP_UNAUTHORIZED,
		);
	}

	public function logout(): JsonResponse
	{
		Auth::logout();
		return response()->json(['status' => 'success']);
	}

	public function refresh(): JsonResponse
	{
		try {
			$token = $this->guard()->refresh();
			return response()
				->json(['status' => 'success'])
				->header('Authorization', $token);
		} catch (\Exception $e) {
			return response()->json(
				['errors' => ['account' => 'Can not refresh token']],
				Response::HTTP_UNAUTHORIZED,
			);
		}
	}
}
