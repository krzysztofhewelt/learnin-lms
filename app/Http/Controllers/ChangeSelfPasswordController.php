<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeSelfPasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ChangeSelfPasswordController extends Controller
{
    public function changePassword(ChangeSelfPasswordRequest $request) : JsonResponse
    {
        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->update();

        return response()->json(['success' => 'Password successfully updated']);
    }
}
