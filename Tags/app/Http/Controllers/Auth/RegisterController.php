<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = bcrypt($validatedData['password']);

        $user = User::create($validatedData);
        $verificationCode = rand(100000, 999999);

        $user->verification_code = $verificationCode;
        $user->save();

        Log::info("Verification code for user {$user->id}: {$verificationCode}");

        $user->token = $user->createToken('TagsTask')->plainTextToken;

        return ApiResponse::sendResponse(201, 'User Account Created Successfully', new UserResource($user));

    }


}
