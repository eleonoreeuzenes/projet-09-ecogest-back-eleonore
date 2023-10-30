<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Post;
use App\Notifications\PostLiked;

class NotificationsController extends Controller
{

    public function index()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $index = 0;
        // foreach ($user->notifications as $notification) {
        //     $index++;
        // } 

        $index = $user->notifications->count();

        
        return response()->json($index);
    }


}