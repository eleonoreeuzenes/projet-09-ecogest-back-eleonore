<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PostService;
use App\Services\UserService;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
    public function getResult(String $q)
    {
        // Participants list with details
        $users = UserService::searchByUsernameOrEmail($q);
        $result['users'] = $users;

        // Posts list with details
        $posts = PostService::searchByTitleOrDescriptionOrTag($q);
        $result['posts'] = $posts;
        
        return response()->json($result);
    }
}
