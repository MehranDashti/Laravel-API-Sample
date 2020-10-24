<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('db:seed');
        Artisan::call('passport:install');
    }
}
