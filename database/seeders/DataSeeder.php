<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class DataSeeder extends Seeder
{
    private function getData(): array
    {
        return json_decode(Storage::get('seed.json'), true);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = $this->getData();

        foreach ($data['tasks'] as $item) {
            try {
                Task::findOrFail($item['id'])->first();
            } catch (ModelNotFoundException $e) {
                Task::create($item);
            }
        }
    }
}
