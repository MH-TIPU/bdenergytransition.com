<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\NewsItem;
use App\Models\TimelineEvent;
use App\Models\Quote;

Route::get('/', function () {
    $categories = Category::query()
        ->whereHas('newsItems', fn ($query) => $query->published())
        ->orderBy('name')
        ->get();

    $footerCategories = Category::query()
        ->where('show_in_footer', true)
        ->orderBy('name')
        ->get();

    $activeCategorySlug = request()->string('category')->toString();
    $activeCategory = $activeCategorySlug !== ''
        ? $categories->firstWhere('slug', $activeCategorySlug)
        : null;

    $baseNewsQuery = NewsItem::query()
        ->published()
        ->with('categories')
        ->orderByDesc(DB::raw('coalesce(published_at, created_at)'));

    $heroNews = (clone $baseNewsQuery)
        ->where('is_hero', true)
        ->take(3)
        ->get();

    if ($heroNews->count() < 3) {
        $fill = (clone $baseNewsQuery)
            ->when(
                $heroNews->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $heroNews->pluck('id')),
            )
            ->take(3 - $heroNews->count())
            ->get();

        $heroNews = $heroNews->concat($fill);
    }

    $featuredNews = (clone $baseNewsQuery)
        ->where('is_featured', true)
        ->take(3)
        ->get();

    if ($featuredNews->count() < 3) {
        $fill = (clone $baseNewsQuery)
            ->when(
                $featuredNews->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $featuredNews->pluck('id')),
            )
            ->when(
                $heroNews->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $heroNews->pluck('id')),
            )
            ->take(3 - $featuredNews->count())
            ->get();

        $featuredNews = $featuredNews->concat($fill);
    }

    if ($featuredNews->count() < 3) {
        $fill = (clone $baseNewsQuery)
            ->when(
                $featuredNews->isNotEmpty(),
                fn ($query) => $query->whereNotIn('id', $featuredNews->pluck('id')),
            )
            ->take(3 - $featuredNews->count())
            ->get();

        $featuredNews = $featuredNews->concat($fill);
    }

    $timelineMilestones = TimelineEvent::query()
        ->published()
        ->whereNotNull('event_date')
        ->orderBy('event_date', 'asc')
        ->get();

    $quotes = Quote::query()
        ->published()
        ->latest()
        ->get();

    return view('welcome', compact('heroNews', 'featuredNews', 'timelineMilestones', 'quotes', 'categories', 'footerCategories', 'activeCategory'));
});
