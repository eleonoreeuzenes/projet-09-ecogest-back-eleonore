<?php

namespace App\Http\Controllers\Api;

use App\Services\PostService;
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

        foreach ($posts as $post) {
            $post->userPostParticipation;
            $post->category;
            $post->like; 
            $post->comment;
            $post->user;
        }

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
            "tag" => "nullable|array",
            "tag.*" => "nullable|string",
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'position' => 'nullable|string|max:255',
            'type' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $category = Category::where('id', $request['category_id'])->first();
        if (!$category) {
            return response()->json(['error' => 'Category not found.'], 404);
        }

        if ($validated['type'] == 'challenge') {
            if ($request['start_date'] == null || $request['end_date'] == null) {
                return response()->json(['error' => 'Start date or end date can not be null.'], 400);
            }
        }

        $validated['author_id'] = $user->id;
        $validated['category_id'] = $category->id;
        if ($request['tag']) {
            $validated['tag'] = implode($request['tag.*']);
            // it's a solution to convert string to array from laravel to postgresql
        }
        $post = Post::create($validated);
        PostService::addAuthorPostToUserPostParticipation($post);

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

        $post->userPostParticipation;
        $post->category;
        $post->like; 
        $post->comment;
        $post->user;

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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validated['type'] == 'challenge') {
            if ($request['start_date'] == null || $request['end_date'] == null) {
                return response()->json(['error' => 'Start date or end date can not be null.'], 400);
            }
        }

        if ($request['tag']) {
            $validated['tag'] = implode($request['tag.*']);
            // it's a solution to convert string to array from laravel to postgresql
        }

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