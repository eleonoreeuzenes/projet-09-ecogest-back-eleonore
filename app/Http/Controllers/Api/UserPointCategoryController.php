<?php

namespace App\Http\Controllers\Api;

use App\Models\UserPointCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class UserPointCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPointCategory = UserPointCategory::where('user_id', $user->id)->get();

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
            'current_point' => 'required',
            'total_point' => 'required',
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
            'current_point' => $validated['current_point'],
            'total_point' => $validated['total_point'],
        ]);

        $userPointCategory->save();
        return response()->json($userPointCategory);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $categoryId)
    {
        $user = auth()->user();

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
    public function update(Request $request, int $categoryId)
    {
        $user = auth()->user();

        $userPointCategory = UserPointCategory::where(['user_id' => $user->id, 'category_id' => $categoryId])->first();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'current_point' => 'required',
            'total_point' => 'required',
        ]);

        $userPointCategory->update($validated);

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