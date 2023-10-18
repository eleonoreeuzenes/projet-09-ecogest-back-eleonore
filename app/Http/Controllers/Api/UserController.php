<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\UserPointCategory;
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

  public function getUserData()
  {
    $user = $this->getUser();

    if (!$user) {
      return response()->json(['error' => 'User not found.'], 404);
    }

    $user->badge;
    $user->userTrophy;
    $user->userPostParticipation;
    $user->follower->load('follower');
    $user->following->load('following');

    return response()->json($user);
  }

  public function show(int $userId)
  {
    $userAuthenticated = $this->getUser();

    $user = User::where('id', $userId)->first();

    if (!$user) {
      return response()->json(['error' => 'User not found.'], 404);
    }

    if ($userAuthenticated->following->load('following')->where('status', 'approved')->contains('following_id', $userId) || $user->is_private) {
      $user->badge;
      $user->userTrophy;
      $user->userPostParticipation;
      $user->follower->load('follower');
      $user->following->load('following');
    }

    return response()->json($user);
  }

  public function update(Request $request)
  {
    $user = $this->getUser();

    if ($user === null) {
      return response()->json([
        'message' => 'User not found.'
      ], 404);
    }


    $validated = $request->validate([
      'email' => 'string|email',
      'username' => 'nullable|string|max:255',
      'image' => 'nullable|string|max:255',
      'badge_id' => 'integer',
      'birthdate' => 'nullable|date_format:d/m/Y',
      'biography' => 'nullable|string',
      'position' => 'nullable|string|max:255',
      "is_private" => 'boolean'
    ]);

    $user->update($validated);

    return response()->json($user);
  }

  public function destroy()
  {
    $user = $this->getUser();

    if (!$user) {
      return response()->json(['error' => 'User not found.'], 404);
    }
    $user->delete();
  }

}