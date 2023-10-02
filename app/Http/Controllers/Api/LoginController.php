<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
  public function login(Request $request)
  {
    if (!Auth::attempt($request->only('email', 'password'))) {
      return response()->json([
        'message' => 'Login information is invalid.'
      ], 400);
    }

    $user = User::where('email', $request['email'])->firstOrFail();
    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json([
      'access_token' => $token,
      'token_type' => 'Bearer',
    ]);
  }
}