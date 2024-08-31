<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerifyUserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function verify(VerifyUserRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();
        if ($user && $user->verification_code == $request->verification_code) {
            $user->phone_verified_at = now();
            $user->save();
            return ApiResponse::sendResponse(200,'Account verified successfully');
        }
            return ApiResponse::sendResponse(400,'Invalid verification code');
    }

}
