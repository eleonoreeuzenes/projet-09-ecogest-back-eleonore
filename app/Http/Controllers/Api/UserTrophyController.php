<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Models\UserTrophy;
use Illuminate\Http\Request;

class UserTrophyController extends Controller
{

    /**
     * Recover all trophies of a user.
     */
    public function index(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userTrophies = UserTrophy::where('user_id', $user->id)->get();

        if (!$userTrophies) {
            return response()->json(['error' => 'Trophy of user not found.'], 404);

        }

        foreach ($userTrophies as $userTrophy) {
            $userTrophy->categories;
        }
        return response()->json($userTrophies);
    }

    /**
     * Store a new trophy for user.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'category_id' => 'required',
        ]);

        $category = Category::where('id', $request['category_id'])->first();
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $userTrophy = UserTrophy::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $userTrophy->save();
        return response()->json($userTrophy);
    }

    /**
     * Display a trophy of a user.
     */
    public function show(int $userId, int $categoryId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userTrophy = UserTrophy::where(['user_id' => $user->id, 'category_id' => $categoryId])->first();

        if (!$userTrophy) {
            return response()->json(['error' => 'Trophy not found.'], 404);
        }

        $userTrophy->categories;
        return response()->json($userTrophy);
    }

    /**
     * Update a trophy of a user.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $userTrophy = UserTrophy::where('user_id', $user->id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'category_id' => 'nullable|integer',
        ]);

        $userTrophy->update($validated);

        return response()->json($userTrophy);
    }

    /**
     * Remove a trophy of a user.
     */
    public function destroy(int $id)
    {
        $userTrophy = UserTrophy::where('id', $id)->firstOrFail();

        $userTrophy->delete();
    }
}