<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\UserPointCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class UserPointCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPointCategory = UserPointCategory::where('user_id', $userId)->get();

        if (!$userPointCategory) {
            return response()->json(['error' => 'User point category not found.'], 404);

        }

        return response()->json($userPointCategory);
    }

    /**
     * Store a newly created resource in storage.
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

        $userPointCategoryAlreadyExists = UserPointCategory::where(['user_id' => $user->id, 'category_id' => $validated['category_id']])->count();
        if ($userPointCategoryAlreadyExists > 0) {
            return response()->json(['error' => 'User has already points in this cateogry.'], 400);
        }

        $userPointCategory = UserPointCategory::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $userPointCategory->save();
        return response()->json($userPointCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $userId, int $categoryId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPointCategory = UserPointCategory::where(['user_id' => $user->id, 'category_id' => $categoryId])->first();

        if (!$userPointCategory) {
            return response()->json(['error' => 'User point category not found.'], 404);
        }

        return response()->json($userPointCategory);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(int $categoryId)
    {
        $user = auth()->user();

        $userPointCategory = UserPointCategory::where(['user_id' => $user->id, 'category_id' => $categoryId])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        
        $userPointCategory->update();

        return response()->json($userPointCategory);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $userPointCategory = UserPointCategory::where('id', $id)->firstOrFail();

        $userPointCategory->delete();
    }
}