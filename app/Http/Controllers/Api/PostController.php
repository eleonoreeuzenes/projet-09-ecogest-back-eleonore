<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'DESC')->paginate(30);
        return response()->json($posts);
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
            'category_id' => 'required|integer',
            "tag" => "array",
            "tag.*" => "nullable|string",
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'level' => 'required|string|max:255',
        ]);

        $category = Category::where('id', $request['category_id'])->first();
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        $validated['author_id'] = $user->id;
        $validated['category_id'] = $category->id;
        $validated['tag'] = implode($request['tag.*']);
        // it's a solution to convert string to array from laravel to postgresql


        $post = Post::create($validated);

        $post->save();
        return response()->json($validated);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $post = Post::where('id', $id)->firstOrFail();

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $user = auth()->user();


        if ($user === null) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $post = Post::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            "tag" => "array",
            "tag.*" => "nullable|string",
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'string|max:255',
            'level' => 'string|max:255',
        ]);

        $validated['tag'] = implode($request['tag.*']);
        // it's a solution to convert string to array from laravel to postgresql

        $post->update($validated);

        return response()->json($post);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $post = Post::where('id', $id)->firstOrFail();
        $post->delete();
    }
}