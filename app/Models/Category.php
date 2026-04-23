<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'show_in_footer'];

    protected $casts = [
        'show_in_footer' => 'boolean',
    ];

    public function newsItems()
    {
        return $this->belongsToMany(NewsItem::class);
    }
}
