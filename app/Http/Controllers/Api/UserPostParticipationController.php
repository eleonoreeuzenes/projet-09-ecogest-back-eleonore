<?php

namespace App\Http\Controllers\Api;

use App\Models\Reward;
use App\Models\UserPostParticipation;
use App\Services\PostService;
use App\Services\UserPointService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use App\Models\UserPointCategory;

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
        $userAlreadyParticipates = UserPostParticipation::where(['ca' => $postId, 'participant_id' => $user->id])->first();

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

        $userPostParticipation->update();

        return response()->json($userPostParticipation);
    }

}