<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class LoginTest extends TestCase
{
    public function testRequiresEmailAndLogin()
    {
        $this->json('POST', '/api/v1/login')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }

    public function testUserLoginSuccessfully()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => 'pass123'];

        $this->json('POST', '/api/v1/login', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'access_token',
                'expires_in',
            ]);
    }

    public function testUserLoginFailed()
    {
        $user = factory(User::class)->create([
            'email' => 'testlogin@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $payload = ['email' => 'testlogin@user.com', 'password' => '7896543'];

        $this->json('POST', '/api/v1/login', $payload)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'user credentials is wrong',
            ]);

    }
}
