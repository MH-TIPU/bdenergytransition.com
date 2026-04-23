<?php

namespace Tests\Feature;

use App\Models\NewsItem;
use App\Models\Quote;
use App\Models\TimelineEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepagePublishedContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_hides_unpublished_and_future_dated_content(): void
    {
        NewsItem::query()->create([
            'title' => 'Visible News',
            'excerpt' => 'Shown',
            'is_published' => true,
            'published_at' => now()->subDay(),
        ]);

        NewsItem::query()->create([
            'title' => 'Visible No Date',
            'excerpt' => 'Shown',
            'is_published' => true,
            'published_at' => null,
        ]);

        NewsItem::query()->create([
            'title' => 'Draft News',
            'excerpt' => 'Hidden',
            'is_published' => false,
            'published_at' => now()->subDay(),
        ]);

        NewsItem::query()->create([
            'title' => 'Future News',
            'excerpt' => 'Hidden',
            'is_published' => true,
            'published_at' => now()->addDay(),
        ]);

        Quote::query()->create([
            'quote_text' => 'Visible Quote',
            'author_name' => 'Alice',
            'is_published' => true,
        ]);

        Quote::query()->create([
            'quote_text' => 'Hidden Quote',
            'author_name' => 'Bob',
            'is_published' => false,
        ]);

        TimelineEvent::query()->create([
            'event_date' => '2026-04-05',
            'global_event_title' => 'Visible Event A',
            'global_event_excerpt' => 'Global excerpt A',
            'impact_title' => 'Visible Impact A',
            'impact_excerpt' => 'Impact excerpt A',
            'is_published' => true,
        ]);

        TimelineEvent::query()->create([
            'event_date' => '2026-04-22',
            'global_event_title' => 'Visible Event B',
            'global_event_excerpt' => 'Global excerpt B',
            'impact_title' => 'Visible Impact B',
            'impact_excerpt' => 'Impact excerpt B',
            'is_published' => true,
        ]);

        TimelineEvent::query()->create([
            'event_date' => '2026-04-10',
            'global_event_title' => 'Hidden Event',
            'impact_title' => 'Hidden Impact',
            'is_published' => false,
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Visible News');
        $response->assertSee('Visible No Date');
        $response->assertDontSee('Draft News');
        $response->assertDontSee('Future News');
        $response->assertSee('Visible Quote');
        $response->assertDontSee('Hidden Quote');
        $response->assertSee('Visible Event A');
        $response->assertSee('Visible Impact A');
        $response->assertSee('Visible Event B');
        $response->assertSee('Visible Impact B');
        $response->assertDontSee('Hidden Event');
        $response->assertDontSee('Hidden Impact');
    }
}
