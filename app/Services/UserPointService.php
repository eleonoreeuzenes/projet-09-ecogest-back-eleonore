<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use App\Models\UserPointCategory;
use App\Models\Reward;
use App\Models\UserTrophy;
use DateTime;

class UserPointService
{
    public static function newTrophy(UserPointCategory $userPointCategory)
    {
        $userTrophy = UserTrophy::create([
            'user_id' => $userPointCategory->user_id,
            'category_id' => $userPointCategory->category_id,
        ]);
        $userTrophy->save();
    }

    public static function updateUserCurrentPointCategory(Post $post, UserPointCategory $userPointCategory)
    {
        if (isset($post->start_date) && isset($post->end_date)) {
            $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
            $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
            $nbDays = $start_date->diff($end_date)->days;
        } else {
            $nbDays = 1;
        }

        $nbPoint = $userPointCategory->current_point + (self::getLevelInPoints($post->level) * $nbDays);
        $reward = Reward::where('type', 'trophy')->firstOrFail();
        UserPointService::updateUserTotalPointCategory($post, $userPointCategory);
        if ($nbPoint < $reward->point) {
            $userPointCategory->current_point = $nbPoint;
        } else {
            $newCurrentPoint = $nbPoint;
            while ($newCurrentPoint >= $reward->point) {
                $newCurrentPoint = $newCurrentPoint - $reward->point;
            }
            $userPointCategory->current_point = $newCurrentPoint;
            self::newTrophy($userPointCategory);
        }
        $userPointCategory->save();
    }

    public static function updateUserCurrentPointCategoryUpdatedPost(Post $post, array $updatedPost, UserPointCategory $userPointCategory)
    {

        if (
            $updatedPost['end_date'] != $post->end_date ||
            $updatedPost['start_date'] != $post->start_date ||
            $updatedPost['level'] != $post->level
        ) {
            if (isset($post->start_date) && isset($post->end_date)) {
                $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
                $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
                $nbDays = $start_date->diff($end_date)->days;
            } else {
                $nbDays = 1;
            }

            if (isset($updatedPost['start_date']) && isset($updatedPost['end_date'])) {
                $start_date_updated = new DateTime(date("Y-m-d", strtotime($updatedPost['start_date'])));
                $end_date_updated = new DateTime(date("Y-m-d", strtotime($updatedPost['end_date'])));
                $updatedPostNbDays = $start_date_updated->diff($end_date_updated)->days;
            } else {
                $updatedPostNbDays = 1;
            }

            $nbPoint = $userPointCategory->current_point + (self::getLevelInPoints($post->level) * $nbDays);
            $nbPointUpdated = $userPointCategory->current_point + (self::getLevelInPoints($updatedPost['level']) * $updatedPostNbDays);

            if ($nbPoint > $nbPointUpdated) {
                $userPointCategory->total_point = $userPointCategory->total_point - ($nbPoint - $nbPointUpdated);
            } else {
                $userPointCategory->total_point = $userPointCategory->total_point + ($nbPointUpdated - $nbPoint);
            }
            $userPointCategory->save();
        }
    }


    public static function updateUserTotalPointCategory(Post $post, UserPointCategory $userPointCategory)
    {
        $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
        $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
        $nbDays = $start_date->diff($end_date)->days;
        $userPointCategory->total_point = $userPointCategory->total_point + (self::getLevelInPoints($post->level) * $nbDays);
        $userPointCategory->save();
    }

    private static function getLevelInPoints(string $level)
    {
        switch ($level) {
            case 'easy':
                return 10;
            case 'medium':
                return 20;
            case 'hard':
                return 30;
        }
    }

    public static function setNewBadge(User $user)
    {
        $userPointCategories = UserPointCategory::select('total_point')->where('user_id', $user->id)->get();
        $userTotalPoints = 0;
        foreach ($userPointCategories as $userPointCategory) {
            $userTotalPoints += $userPointCategory->total_point;
        }
        $whereData = [
            ['type', 'badge'],
            ['point', '<=', $userTotalPoints]
        ];
        $reward = Reward::orderBy('point', 'DESC')->where($whereData)->firstOrFail();
        $user->badge_id = $reward->id;
        $user->save();
    }
    public static function userTotalPoints($userId): int
    {
        $user = User::where("id", $userId)->firstOrFail();

        $userPointCategories = UserPointCategory::select('total_point')->where('user_id', $user->id)->get();
        $userTotalPoints = 0;
        foreach ($userPointCategories as $userPointCategory) {
            $userTotalPoints += $userPointCategory->total_point;
        }
        return $userTotalPoints;
    }
}