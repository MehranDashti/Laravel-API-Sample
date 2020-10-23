<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();
        $password = Hash::make('pass123');
        User::create([
            'name' => 'admin',
            'email' => 'admin@test.com',
            'password' => $password,
            'role_id' => User::ROLE_ID_ADMIN,
        ]);
    }
}
