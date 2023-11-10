<?php

namespace Tests\Feature;

use App\Models\Reward;
use Tests\TestCase;
use App\Models\User;

use function PHPUnit\Framework\isTrue;

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
        $userData = [
            "email" => "doe@example.com",
            "username" => "doe-example",
            "password" => "Demo!12345",
        ];

        $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function testIfRegisterReturnsToken()
    {
        $userData = [
            "email" => "doe@example.com",
            "username" => "doe-example",
            "password" => "Demo!12345",
        ];

        $res = $this->json('POST', 'api/register', $userData, ['Accept' => 'application/json']);
        $isSetToken = isset($res['access_token']);
        $this->assertTrue($isSetToken);
    }

    public function testGetUserSuccess()
    {
        $user = User::create([
            "email" => "doe@example.com",
            "password" => "Demo!12345",
        ]);
        $token = $user->createToken('authToken')->plainTextToken;

        $this->withHeaders(['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $token])
            ->get('api/me')
            ->assertStatus(200);
    }
}