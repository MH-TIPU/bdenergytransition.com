<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Energy Policy',
            'LNG & Gas',
            'Renewables',
            'Power Grid',
            'Climate & Finance',
            'Electricity Tariffs',
            'Energy Efficiency',
            'Transmission',
            'Industry & Trade',
            'Technology',
        ];

        foreach ($names as $idx => $name) {
            Category::query()->updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'show_in_footer' => $idx < 4,
                ]
            );
        }
    }
}
