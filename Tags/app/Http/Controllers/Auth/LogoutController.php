<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200,'Logged Out Successfully');
    }
}
