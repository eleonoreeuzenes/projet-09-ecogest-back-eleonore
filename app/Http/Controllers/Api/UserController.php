<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Recipient;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function getUser()
    {
        $user = auth()->user();
        $user = User::where('id', $user->id)->firt();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        return $user;
    }
}