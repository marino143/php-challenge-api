<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private array $general = [
        UserSeeder::class,
    ];

    private array $data = [
        DataSeeder::class,
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call($this->general);

        if (env('APP_ENV') === 'local') {
            $this->call($this->data);
        }
    }
}
