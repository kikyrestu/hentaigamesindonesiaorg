<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavigationItem extends Model
{
    protected $fillable = [
        'label',
        'url',
        'sort_order',
        'is_external',
    ];

    protected $casts = [
        'is_external' => 'boolean',
    ];
}
