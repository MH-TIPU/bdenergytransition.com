<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\NewsItem;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class NewsItemsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->get();
        $tags = Tag::query()->get();

        for ($i = 1; $i <= 10; $i += 1) {
            $title = 'Energy update #' . $i;
            $news = NewsItem::query()->updateOrCreate(
                ['title' => $title],
                [
                    'description' => fake()->paragraph(2),
                    'content' => collect(fake()->paragraphs(6))->implode("\n\n"),
                    'excerpt' => fake()->sentence(18),
                    'src_link' => 'https://example.com/news/' . $i,
                    'feature_image' => null,
                    'author' => fake()->name(),
                    'published_at' => now()->subDays(fake()->numberBetween(1, 60)),
                    'is_published' => true,
                    'is_hero' => $i <= 3,
                    'is_featured' => $i >= 4 && $i <= 6,
                ]
            );

            if ($categories->isNotEmpty()) {
                $news->categories()->sync(
                    $categories->random(fake()->numberBetween(1, min(2, $categories->count())))->pluck('id')->all()
                );
            }

            if ($tags->isNotEmpty()) {
                $news->tags()->sync(
                    $tags->random(fake()->numberBetween(1, min(3, $tags->count())))->pluck('id')->all()
                );
            }
        }

        // A couple of hidden items (draft/future)
        NewsItem::query()->updateOrCreate(
            ['title' => 'Draft news (hidden)'],
            [
                'description' => fake()->paragraph(2),
                'content' => collect(fake()->paragraphs(4))->implode("\n\n"),
                'excerpt' => fake()->sentence(16),
                'src_link' => null,
                'feature_image' => null,
                'author' => fake()->name(),
                'published_at' => now()->subDays(3),
                'is_published' => false,
            ]
        );

        NewsItem::query()->updateOrCreate(
            ['title' => 'Future news (hidden)'],
            [
                'description' => fake()->paragraph(2),
                'content' => collect(fake()->paragraphs(4))->implode("\n\n"),
                'excerpt' => fake()->sentence(16),
                'src_link' => null,
                'feature_image' => null,
                'author' => fake()->name(),
                'published_at' => now()->addDays(5),
                'is_published' => true,
            ]
        );
    }
}
