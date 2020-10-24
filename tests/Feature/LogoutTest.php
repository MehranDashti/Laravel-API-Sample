<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    private $tokenResult;
    private $headers;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $this->tokenResult = $user->createToken('Laravel Password Grant Client');
        $this->headers = ['Authorization' => "Bearer {$this->tokenResult->accessToken}"];
    }

    public function testLogoutFailed()
    {
        $this->tokenResult->token->revoke();

        $this->json('POST', '/api/v1/logout', [], $this->headers)
            ->assertStatus(401)
            ->assertExactJson([
                'message' => 'Unauthenticated.'
            ]);
    }

    public function testLogoutSuccesfully()
    {
        $this->json('POST', '/api/v1/logout', [], $this->headers)
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'You have been successfully logged out!'
            ]);
    }
}
