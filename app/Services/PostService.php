<?php

namespace App\Services;

use App\Models\Post;
use App\Models\UserPointCategory;
use App\Models\UserPostParticipation;
use DateTime;

class PostService
{

    public static function addAuthorPostToUserPostParticipation(Post $post)
    {
        if ($post->type === 'action') {
            $isCompleted = true;
        } else {
            $isCompleted = $post->end_date >= new DateTime() ? true : false;
        }

        $userPostParticipation = UserPostParticipation::create([
            'participant_id' => $post->author_id,
            'post_id' => $post->id,
            'is_completed' => $isCompleted
        ]);
        $userPostParticipation->save();

        self::createUserPointCategoryWithZeroPoint($post, $userPostParticipation->participant_id);
    }

    public static function createUserPointCategoryWithZeroPoint(Post $post, int $partcipantId)
    {
        $userPointCategoryAlreadyExists = UserPointCategory::where(['user_id' => $partcipantId, 'category_id' => $post->category_id])->count();
        if ($userPointCategoryAlreadyExists == 0) {
            $userPointCategory = UserPointCategory::create([
                'user_id' => $partcipantId,
                'category_id' => $post->category_id,
                'current_point' => 0,
                'total_point' => 0,
            ]);

            $userPointCategory->save();
        }
    }

    public static function searchByTitleOrDescriptionOrTag(string $q)
    {
        // Participant lists with details
        $posts = Post::where('title', 'ILIKE', '%' . $q . '%')
            ->orWhere('description', 'ILIKE', '%' . $q . '%')
            ->orWhere('tag', 'ILIKE', '%' . $q . '%')
            ->take(10)
            ->get();

        foreach ($posts as $post) {
            foreach ($post->userPostParticipation as $userPostParticipation) {
                $userPostParticipation->users;
            }
            $post->category;
            $post->tag;
            $post->like;
            $post->comment;
            $post->user->badge;
        }

        return $posts;
    }
}