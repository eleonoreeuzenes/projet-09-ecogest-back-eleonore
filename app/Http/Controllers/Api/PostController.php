<?php

namespace App\Http\Controllers\Api;

use App\Models\Reward;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PostService;
use App\Services\TagService;
use App\Models\Category;
use App\Services\UserPointService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\UserPointCategory;
use App\Models\UserTrophy;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $posts = Post::orderBy('created_at', 'DESC')->paginate(8);

        $postsOfUserCommunity = [];

        foreach ($posts as $post) {
            if ($user->following->load('follower')->where('status', 'approved')->contains('following_id', $post->author_id) || $user->id == $post->author_id || !$user->is_private) {
                foreach ($post->userPostParticipation as $userPostParticipation) {
                    $userPostParticipation->users;
                }
                $post->category;
                $post->tags->setHidden([
                    "created_at",
                    "updated_at",
                    "pivot"]);
                $post->like;
                $post->comment->load('users');
                $post->user->badge;
                $postsOfUserCommunity[] = $post;
            }
        }

        return response()->json($postsOfUserCommunity);
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
            "tags" => "nullable|array",
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

        $post = Post::create($validated);
        PostService::addAuthorPostToUserPostParticipation($post);

        $userPointCategory = UserPointCategory::where('user_id', $user->id)->where('category_id', $category->id)->first();
        UserPointService::updateUserCurrentPointCategory($post, $userPointCategory);
        
        $userModel = User::where('id', $user->id)->firstOrFail();
        UserPointService::setNewBadge($userModel);

        $post->save();
          
        // If user adds tags
        if (isset($validated['tags'])) {
            $tagsToAttach = TagService::addTagsToPost($validated['tags']);
            foreach($tagsToAttach as $tagId) {
                $post->tags()->attach($tagId);
            }
        }

        return response()->json($validated);
    }


    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $user = auth()->user();
        $userAuthenticated = auth()->user();
        
        if (!$userAuthenticated || !$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        if ($user->is_private) {
            $userAuthenticatedFollowing =  Subscription::where(['status' => 'approved', 'following_id' => $user->id, 'follower_id' => $userAuthenticated->id]);
            if ($userAuthenticatedFollowing->count() < 1 && $user->id != $userAuthenticated->id) {
                return response()->json(['error' => 'User private'], 400);
            }
        }

        $post = Post::where('id', $id)->firstOrFail();

        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        foreach ($post->userPostParticipation as $userPostParticipation) {
            $userPostParticipation->users;
        }
        $post->category;
        $post->like;
        $post->tags->setHidden([
            "created_at",
            "updated_at",
            "pivot"]);
        $post->comment;
        $post->comment->load('users');
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
            "tags" => "nullable|array",
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

        if ($validated['tags']) {
            $post = TagService::updateTagsToPost($post, $validated['tags']);
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

    public function getPostsByTag(string $tag)
    {
        $user = auth()->user();

        if ($user === null) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $posts = PostService::getPostsByTag($tag);

        foreach ($posts as $post) {
            $post->category;
            $post->like;
            $post->setHidden(["pivot"]);
            $post->tags->setHidden([
                "created_at",
                "updated_at",
                "pivot"]);
            $post->comment;
            $post->user;
        }

        if ($posts === null) {
            return response()->json(['error' => 'Tag not found.'], 404);
        }
        return response()->json($posts);
    }
}