<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\Post;
use App\Models\User;
use Exception;

class StatsController extends Controller
{
    public function stats()
    {
        try {
            $stats = cache()->remember('stats', now()->addMinutes(10), function () {
                return [
                    'total_users' => User::count(),
                    'total_posts' => Post::count(),
                    'users_with_no_posts' => User::doesntHave('posts')->count(),
                ];
            });
            return ApiResponse::sendResponse(200,'Posts and Users Stats',$stats);
        } catch (Exception $e) {
            return ApiResponse::sendResponse(500,'Fail to load Stats');
        }
    }

}
