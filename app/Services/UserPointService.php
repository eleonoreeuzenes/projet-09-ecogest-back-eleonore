<?php

namespace App\Services;

use App\Models\Post;
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

    public static function updateUserCurrentPointCategory(Post $post, UserPointCategory $userPointCategory, Reward $reward)
    {
        $start_date = new DateTime (date("Y-m-d", strtotime($post->start_date)));
        $end_date = new DateTime (date("Y-m-d", strtotime($post->end_date)));
        $nbDays = $start_date->diff($end_date)->days;
        $nbPoint = $userPointCategory->current_point + (self::getLevelInPoints($post->level) * $nbDays);
        if ($nbPoint < $reward->point) {
            $userPointCategory->current_point = $nbPoint;
        } else {
            $userPointCategory->current_point = $nbPoint - $reward->point;
            self::newTrophy($userPointCategory);
        }
        $userPointCategory->save();
    }

    public static function updateUserTotalPointCategory(Post $post, UserPointCategory $userPointCategory)
    {
        $start_date = new DateTime (date("Y-m-d", strtotime($post->start_date)));
        $end_date = new DateTime (date("Y-m-d", strtotime($post->end_date)));
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

}