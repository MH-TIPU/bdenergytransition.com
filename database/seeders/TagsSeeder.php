<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagsSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Bangladesh',
            'Global Markets',
            'Fuel Prices',
            'Energy Security',
            'Solar',
            'Wind',
            'Coal',
            'LNG',
            'Grid',
            'Carbon Markets',
        ];

        foreach ($names as $name) {
            Tag::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
