<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request
     * @return mixed
     */
    public function register(Request $request)
    {
        $validatorEmail = Validator::make($request->all(), [
            'email' => 'required|email'
        ]);

        if ($validatorEmail->fails()) {
            return response()->json([
                'message' => 'Email format is invalid.'
            ], 400);
        }

        if (User::where('email', $request['email'])->count() > 0) {
            return response()->json([
                'message' => 'Email already used.'
            ], 400);
        }

        $validatorPassword = Validator::make($request->all(), [
            'password' => [
                'required',
                'min:8',
                'regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/'
            ]
        ]);

        if ($validatorPassword->fails()) {
            return response()->json([
                'message' => 'Password format is invalid.'
            ], 400);
        }

        $user = User::create([
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}