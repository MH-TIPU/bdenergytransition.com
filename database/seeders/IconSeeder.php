<?php

namespace Database\Seeders;

use App\Models\Icon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class IconSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            ['name' => 'Energy', 'slug' => 'energy', 'color' => '#1f6feb', 'letter' => 'E'],
            ['name' => 'Grid', 'slug' => 'grid', 'color' => '#0f766e', 'letter' => 'G'],
            ['name' => 'Policy', 'slug' => 'policy', 'color' => '#b45309', 'letter' => 'P'],
            ['name' => 'Market', 'slug' => 'market', 'color' => '#7c3aed', 'letter' => 'M'],
            ['name' => 'Finance', 'slug' => 'finance', 'color' => '#be123c', 'letter' => 'F'],
            ['name' => 'Renewables', 'slug' => 'renewables', 'color' => '#15803d', 'letter' => 'R'],
        ];

        foreach ($icons as $iconData) {
            $imagePath = 'icons/' . $iconData['slug'] . '.svg';

            Storage::disk('public')->put($imagePath, $this->makeSvg($iconData['letter'], $iconData['color']));

            Icon::query()->updateOrCreate(
                ['name' => $iconData['name']],
                ['image' => $imagePath]
            );
        }
    }

    private function makeSvg(string $letter, string $color): string
    {
        return <<<SVG
<svg width="200" height="200" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="200" height="200" rx="36" fill="{$color}"/>
    <circle cx="100" cy="100" r="58" fill="rgba(255,255,255,0.12)"/>
    <text x="100" y="124" text-anchor="middle" font-size="84" font-family="Arial, Helvetica, sans-serif" font-weight="700" fill="#ffffff">{$letter}</text>
</svg>
SVG;
    }
}
