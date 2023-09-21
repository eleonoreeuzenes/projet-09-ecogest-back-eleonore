<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUser()
    {
        $user = auth()->user();
        $user = User::where('id', $user->id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return $user;
    }

    public function userData()
    {
      $user = $this->getUser();
  
      $user->badge;
  
      return response()->json($user);
    }
}