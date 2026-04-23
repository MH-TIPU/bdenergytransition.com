<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Always ensure an admin exists when seeding intentionally.
        $this->call([
            UsersSeeder::class,
        ]);

        // Sample content (safe default: only local/testing)
        if (! app()->environment('local', 'testing')) {
            return;
        }

        $this->call([
            SettingsSeeder::class,
            CategoriesSeeder::class,
            TagsSeeder::class,
            NewsItemsSeeder::class,
            QuotesSeeder::class,
            TimelineEventsSeeder::class,
        ]);
    }
}
