<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;

class CustomerIndexTest extends TestCase
{
    private $headers;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();
        $result = $user->createToken('Laravel Password Grant Client');
        $this->headers = ['Authorization' => "Bearer {$result->accessToken}"];
    }

    public function testIndex()
    {
        $this->json('GET', '/api/v1/customer', [], $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'current_page',
                'data',
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]);
    }
}
