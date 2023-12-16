<?php

namespace Tests\Feature;

use App\Models\Reward;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use DateTime;


use function PHPUnit\Framework\isTrue;

class PostTest extends TestCase
{
    const CHALLENGEDATA = [
        "category_id" => 7,
        "title" => "Mais si ça marche",
        "description" => "Test4 sans les tags",
        "type" => "challenge",
        "level" => "hard",
        "tags" => [
             ["label" => "velotaf"],
        ],
        "start_date" => "2023-11-04T00:00:00.000",
        "end_date" => "2023-11-28T00:00:00.000"
    ];

    const USERDATA = 
    [
        "email" => "augustin@ecogest.dev",
        "password" => "Password:123"
    ];

    const ACTIONDATA = [
        "category_id" => 7,
        "title" => "Mais si ça marche",
        "description" => "Test4 sans les tags",
        "type" => "action",
        "level" => "hard",
        "tags" => [
             ["label" => "velotaf"],
        ],
    ];

    public function testCreateChallengeStatusCodeSuccess()
    {
        $res = $this->json('POST', 'api/login', self::USERDATA, ['Accept' => 'application/json']);
        $token = $res['access_token'];
        $this->json('POST', 'api/posts', self::CHALLENGEDATA, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token])
            ->assertStatus(200);
    }

    public function testCreateActionStatusCodeSuccess()
    {

        $res = $this->json('POST', 'api/login', self::USERDATA, ['Accept' => 'application/json']);
        $token = $res['access_token'];
        $this->json('POST', 'api/posts', self::ACTIONDATA, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token])
            ->assertStatus(200);
    }

    public function testCategoryIdIsSet()
    {
        $postId = 1;
        $post = Post::where('id', $postId)->firstOrFail();

        $this->assertNotNull($post->category_id);
    }

    public function testLevelIsSet()
    {
        $postId = 1;
        $post = Post::where('id', $postId)->firstOrFail();

        $this->assertNotNull($post->level);
    }

    public function testTypeIsSet()
    {
        $postId = 1;
        $post = Post::where('id', $postId)->firstOrFail();

        $this->assertNotNull($post->type);
    }

    public function testStartDateIsBeforeEndDate()
    {
        $post = Post::where('type', 'challenge')->first();

        $start_date = new DateTime(date("Y-m-d", strtotime($post->start_date)));
        $end_date = new DateTime(date("Y-m-d", strtotime($post->end_date)));
        $nbDays = $start_date->diff($end_date)->days;

        $this->assertTrue($nbDays > 1);
    }

}