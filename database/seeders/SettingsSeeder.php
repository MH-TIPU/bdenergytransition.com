<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->updateOrCreate(
            ['id' => 1],
            [
                'site_name' => 'BD Energy Transition',
                'theme_color' => '#5b21b6',
                'favicon' => null,
                'logo' => null,
                'footer_text' => 'BD Energy Transition Platform',
            ]
        );

        // Extra rows (not used by the app, but useful for demo/testing “at least 10 records”).
        for ($i = 2; $i <= 10; $i += 1) {
            Setting::query()->updateOrCreate(
                ['id' => $i],
                [
                    'site_name' => "BD Energy Transition (Demo {$i})",
                    'theme_color' => '#5b21b6',
                    'favicon' => null,
                    'logo' => null,
                    'footer_text' => "Demo settings row {$i}",
                ]
            );
        }
    }
}
