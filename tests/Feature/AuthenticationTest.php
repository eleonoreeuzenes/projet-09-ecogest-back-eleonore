<?php

namespace Tests\Feature;

use App\Models\Reward;
use App\Models\User;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{

    public function testMustEnterEmailAndPasswordForLogin()
    {
        $this->json('POST', 'api/login')
            ->assertStatus(400);
    }
    public function testMustEnterEmailAndPasswordForResgister()
    {
        $this->json('POST', 'api/register', ['Accept' => 'application/json'])
            ->assertStatus(400);
    }

    public function testSuccessfulLogin()
    {
        $reward = Reward::create([
            'id' => 1,
            "type" => "trophy",
        ]);

        $user = User::create([
            "email" => "doe@example.com",
            "password" => "Demo!12345",
        ]);

        $userData = [
            "email" => "doe@example.com",
            "password" => "Demo!12345",
        ];

        $this->json('POST', 'api/login', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);

        $this->assertAuthenticated();
    }

    public function testSuccessfulRegistration()
    {
        $reward = Reward::create([
            'id' => 1,
            "type" => "trophy",
        ]);

        $userData = [
            "email" => "doe@example.com",
            "password" => "Demo!12345",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function testGetUserSuccess()
    {
        $reward = Reward::create([
            'id' => 1,
            "type" => "trophy",
        ]);

        $user = User::create([
            "email" => "doe@example.com",
            "password" => "Demo!12345",
        ]);
        $token = $user->createToken('authToken')->plainTextToken;

        $this->withHeaders(['Accept' => 'application/json', 'Authorization' => 'Bearer '. $token])
            ->get('api/me')        
            ->assertStatus(200);
    }
}