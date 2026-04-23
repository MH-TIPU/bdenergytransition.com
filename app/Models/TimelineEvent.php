<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class TimelineEvent extends Model
{
    protected $fillable = [
        'event_date',
        'global_event_title',
        'global_event_excerpt',
        'global_event_link',
        'impact_title',
        'impact_excerpt',
        'impact_link',
        'sort_order',
        'is_published',
    ];

    protected $casts = [
        'event_date' => 'date',
        'is_published' => 'boolean',
    ];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
