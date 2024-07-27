<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TodoGroup;
use App\Models\TodoItem;
use Faker\Factory as Faker;

class TodoSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) {
            $group = TodoGroup::create([
                'name' => $faker->word,
            ]);

            for ($j = 0; $j < 5; $j++) {
                $group->todoItems()->create([
                    'title' => $faker->sentence,
                    'is_completed' => $faker->boolean,
                ]);
            }
        }
    }
}
