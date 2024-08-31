<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('phone_number', 'password');

        $user = User::where('phone_number', $credentials['phone_number'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return ApiResponse::sendResponse(401,'Invalid credentials');
        }

        if (!$user->phone_verified_at) {
            return ApiResponse::sendResponse(403,'Account not verified');
        }

        $user->token = $user->createToken('tagsTask')->plainTextToken;

        return ApiResponse::sendResponse(200, 'User Logged In Successfully', new UserResource($user));



    }


}
