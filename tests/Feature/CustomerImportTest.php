<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CustomerImportTest extends TestCase
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create(['role_id' => User::ROLE_ID_ADMIN]);
        $result = $user->createToken('Laravel Password Grant Client');
        $this->headers = ['Authorization' => "Bearer {$result->accessToken}"];
    }

    public function testAuthenticatedUserIsNotAdmin()
    {
        $user = factory(User::class)->create(['role_id' => User::ROLE_ID_NORMAL_USER]);
        $result = $user->createToken('Laravel Password Grant Client');
        $headers = ['Authorization' => "Bearer {$result->accessToken}"];

        $this->json('POST', '/api/v1/customer/import', [], $headers)
            ->assertStatus(403)
            ->assertExactJson([
                'message' => 'Permission Denied'
            ]);
    }

    public function testRequiredCsvFile()
    {
        $this->json('POST', '/api/v1/customer/import', [], $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => ['csv_file' => ['The csv file field is required.']],
            ]);
    }

    public function testUploadedFileIsNotCsv()
    {
        $file = UploadedFile::fake()->create('csv_file.pdf', 2000, 'application/pdf');
        $payload = ['csv_file' => $file];

        $this->json('POST', '/api/v1/customer/import', $payload, $this->headers)
            ->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => ['csv_file' => ['The csv file must be a file of type: csv, txt.']],
            ]);
    }

    public function testImportSuccessfully()
    {
        $header = 'first_name, last_name, phone';
        $row = 'john, doe, 989123456789';
        $content = implode('\n', [$header, $row]);
        $file = UploadedFile::fake()->createWithContent('csv_file.csv', $content);
        $payload = ['csv_file' => $file];

        $this->json('POST', '/api/v1/customer/import', $payload, $this->headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message',
            ]);
    }
}
