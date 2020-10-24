<?php

namespace Tests\Feature;

use App\User;
use Faker\Factory;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    public function testRequiresAttribute()
    {
        $this->json('POST', '/api/v1/register')
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }


    public function testRequiresPasswordConfirmation()
    {
        $factory = Factory::create();
        $payload = ['name' => $factory->name, 'email' => $factory->email, 'password' => $factory->password(6)];
        $this->json('POST', '/api/v1/register', $payload)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'password' => ['The password confirmation does not match.'],
                ]
            ]);
    }

    public function testDuplicateEmail()
    {
        factory(User::class)->create([
            'email' => 'testRegister@user.com',
            'password' => bcrypt('pass123'),
        ]);

        $payload = [
            'name' => 'test',
            'email' => 'testRegister@user.com',
            'password' => '987654321',
            'password_confirmation' => '987654321'
        ];
        $this->json('POST', '/api/v1/register', $payload)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ]
            ]);
    }

    public function testRegisterSuccesfully()
    {
        $payload = [
            'name' => 'test',
            'email' => 'testRegister@user.com',
            'password' => '987654321',
            'password_confirmation' => '987654321'
        ];
        $this->json('POST', '/api/v1/register', $payload)
            ->assertStatus(200)
            ->assertJsonStructure([
                'token_type',
                'access_token',
                'expires_in',
            ]);
    }
}
