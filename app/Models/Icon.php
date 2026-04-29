<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Icon extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    public function timelineEvents(): HasMany
    {
        return $this->hasMany(TimelineEvent::class);
    }
}
