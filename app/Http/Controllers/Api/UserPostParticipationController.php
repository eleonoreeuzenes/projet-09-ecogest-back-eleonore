<?php

namespace App\Http\Controllers\Api;

use App\Models\Reward;
use App\Models\UserPostParticipation;
use App\Services\PostService;
use App\Services\UserPointService;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\UserPointCategory;
use GuzzleHttp\Psr7\Query;

class UserPostParticipationController extends Controller
{
    /**
     * Get the participants of a post
     */
    public function getParticipantsByPostId(int $postId)
    {

        $userPostParticipations = UserPostParticipation::where('post_id', $postId)->get();

        if (!$userPostParticipations) {
            return response()->json(['error' => 'User post participation not found'], 404);
        }

        foreach ($userPostParticipations as $userPostParticipation) {
            $userPostParticipation->users;
        }

        return response()->json($userPostParticipations);
    }

    /**
     * Add a participant on a post
     */
    public function store(Request $request, int $postId)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $validated = $request->validate([
            'is_completed' => 'boolean',
        ]);

        $post = Post::where('id', $postId)->first();
        if (!$post) {
            return response()->json(['error' => 'Post not found.'], 404);
        }

        $userAlreadyParticipates = UserPostParticipation::where(['post_id' => $postId, 'participant_id' => $user->id])->first();

        if ($userAlreadyParticipates) {
            return response()->json(['error' => 'User already participate to this post.'], 400);
        }

        $userPostParticipation = UserPostParticipation::create([
            'participant_id' => $user->id,
            'post_id' => $postId,
            'is_completed' => $request['is_completed'],
        ]);
        $userAlreadyParticipates = UserPostParticipation::where(['post_id' => $postId, 'participant_id' => $user->id])->first();

        PostService::createUserPointCategoryWithZeroPoint($post, $user->id);

        $userPostParticipation->save();
        return response()->json($userPostParticipation);
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

        $userPostParticipation = UserPostParticipation::where(['participant_id' => $user->id, 'id' => $id])->first();

        if (!$userPostParticipation) {
            return response()->json(['error' => 'User post participation not found.'], 404);
        }

        return response()->json($userPostParticipation);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $postId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }
        $validated = $request->validate([
            'is_completed' => 'boolean',
        ]);

        $userPostParticipation = UserPostParticipation::where(['participant_id' => $user->id, 'post_id' => $postId])->firstOrFail();

        $userPostParticipation->update($validated);

        return response()->json($userPostParticipation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $postId, int $userId)
    {
        $userPostParticipation = UserPostParticipation::where(['post_id' => $postId, 'participant_id' => $userId])->firstOrFail();

        $userPostParticipation->delete();
    }


    /**
     * Update the specified resource in storage.
     */
    public function endChallenge(int $postId)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipation = UserPostParticipation::where(['participant_id' => $user->id, 'post_id' => $postId])->firstOrFail();
        $userPostParticipation->is_completed = true;

        $post = Post::where('id', $postId)->firstOrFail();
        $trophy = Reward::where('type', 'trophy')->firstOrFail();
        $userPointCategory = UserPointCategory::where(['user_id' => $user->id, 'category_id' => $post->category_id])->firstOrFail();

        UserPointService::updateUserCurrentPointCategory($post, $userPointCategory, $trophy);
        UserPointService::updateUserTotalPointCategory($post, $userPointCategory);

        $userModel = User::where('id', $user->id)->firstOrFail();
        UserPointService::setNewBadge($userModel);

        $userPostParticipation->update();

        return response()->json($userPostParticipation);
    }

    /**
     * Get all posts by user id
     */
    public function getPostsByUser(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where('participant_id', $user->id)->get();
        $userPostParticipations->load('posts');
        return response()->json($userPostParticipations);
    }

    /**
     * Get posts by user id with is_completed true
     */
    public function getPostsByUserCompleted(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where(['participant_id' => $user->id, 'is_completed' => true])->get();
        $userPostParticipations->load('posts')->where('type', "challenge");

        $userChallenges = [];
        foreach ($userPostParticipations as $userPostParticipation) {
            if ($userPostParticipation->posts->type == 'challenge') {
                $post = $userPostParticipation->posts;
                foreach ($post->userPostParticipation as $userPostParticipation) {
                    $userPostParticipation->users;
                }
                $post->category;
                $post->like;
                $post->comment;
                $post->user;
                $userChallenges[] = $post;
            }
        }

        return response()->json($userChallenges);
    }

    /**
     * Get posts by user id with is_completed false / abandoned
     */
    public function getPostsByUserAbandoned(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where(['participant_id' => $user->id, 'is_completed' => false])->get();
        $userPostParticipations->load('posts')->where('type', "challenge");

        $userPostParticipationsAbandoned = [];
        foreach ($userPostParticipations as $userPostParticipation) {
            $post = $userPostParticipation->posts;
            $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
            if ($userPostParticipation->posts->type == 'challenge') {
                if ($end_date != null && $end_date < new DateTime()) {
                    $post = $userPostParticipation->posts;
                    foreach ($post->userPostParticipation as $userPostParticipation) {
                        $userPostParticipation->users;
                    }
                    $post->category;
                    $post->like;
                    $post->comment;
                    $post->user;
                    $userPostParticipationsAbandoned[] = $post;
                }
            }

        }
        return response()->json($userPostParticipationsAbandoned);
    }

    /**
     * Get posts by user id with is_completed false / in progress
     */
    public function getPostsByUserInProgress(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where(['participant_id' => $user->id, 'is_completed' => false])->get();
        $userPostParticipations->load('posts')->where('type', "challenge");

        $userPostParticipationsInProgress = [];
        foreach ($userPostParticipations as $userPostParticipation) {
            $post = $userPostParticipation->posts;
            $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
            $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
            if ($userPostParticipation->posts->type == 'challenge') {
                if ($start_date < new DateTime() && $end_date > new DateTime()) {
                    $post = $userPostParticipation->posts;
                    foreach ($post->userPostParticipation as $userPostParticipation) {
                        $userPostParticipation->users;
                    }
                    $post->category;
                    $post->like;
                    $post->comment;
                    $post->user;
                    $userPostParticipationsInProgress[]  = $post;
                }
            }
        }
        return response()->json($userPostParticipationsInProgress);
    }


    /**
     * Get posts by user id with is_completed next
     */
    public function getPostsByUserNext(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where(['participant_id' => $user->id, 'is_completed' => false])->get();
        $userPostParticipations->load('posts')->where('type', "challenge");

        $userPostParticipationsNext = [];
        foreach ($userPostParticipations as $userPostParticipation) {
            $post = $userPostParticipation->posts;
            $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
            if ($userPostParticipation->posts->type == 'challenge') {
                if ($start_date != null && $start_date < new DateTime()) {
                    $post = $userPostParticipation->posts;
                    foreach ($post->userPostParticipation as $userPostParticipation) {
                        $userPostParticipation->users;
                    }
                    $post->category;
                    $post->like;
                    $post->comment;
                    $post->user;
                    $userPostParticipationsNext[]  = $post;
                }
            }
        }
        return response()->json($userPostParticipationsNext);
    }

    /**
     * Get posts by user id with => action 
     */
    public function getUserActions(int $userId)
    {
        $user = User::where('id', $userId)->firstOrFail();
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        $userPostParticipations = UserPostParticipation::where(['participant_id' => $user->id, 'is_completed' => true])->get();
        $userActions = [];

        foreach ($userPostParticipations as $userPostParticipation) {
            if ($userPostParticipation->posts->type == 'action') {
                $post = $userPostParticipation->posts;
                foreach ($post->userPostParticipation as $userPostParticipation) {
                    $userPostParticipation->users;
                }
                $post->category;
                $post->like;
                $post->comment;
                $post->user;
                $userActions[] = $post;
            }
        }

        return response()->json($userActions);
    }

}