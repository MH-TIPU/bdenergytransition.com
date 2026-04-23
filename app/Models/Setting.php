<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name',
        'theme_color',
        'favicon',
        'logo',
        'footer_text',
    ];
}
