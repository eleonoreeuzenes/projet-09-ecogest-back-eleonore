<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\UserPointCategory;
use App\Services\UserPointService;
use App\Services\UserService;
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
    $user->follower;
    $user->following;
    $user->total_point = UserPointService::userTotalPoints($user->id);
    
    return response()->json($user);
  }

  public function show(int $userId)
  {
    $userAuthenticated = $this->getUser();

    $user = User::where('id', $userId)->first();

    if (!$user) {
      return response()->json(['error' => 'User not found.'], 404);
    }

    $user->badge;
    $user->total_point = UserPointService::userTotalPoints($user->id);

    if (!UserService::checkIfCanAccessToRessource($user->id)) {
      $user->userTrophy = [];
      $user->userPostParticipation = [];
    } else {
      $user->userTrophy;
      $user->userPostParticipation;
      $user->follower;
      $user->following;
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
      'email' => 'nullable|string|email',
      'username' => 'nullable|string|max:255',
      'image' => 'nullable|string|max:255',
      'badge_id' => 'nullable|integer',
      'birthdate' => 'nullable|date',
      'biography' => 'nullable|string',
      'position' => 'nullable|string|max:255',
      "is_private" => 'nullable|boolean'
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