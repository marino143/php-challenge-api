<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'administrator@test.com',
                'password' => bcrypt('password'),
                'role' => 'administrator',
                'is_verified' => true,
            ],
        ];

        foreach ($users as $user) {
            try {
                User::findOrFail($user['id'])->get();
            } catch (ModelNotFoundException $exception) {
                User::create($user);
            }
        }
    }
}
