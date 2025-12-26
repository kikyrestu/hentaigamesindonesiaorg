<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'title', 'slug', 'cover_image', 'thumbnail_image', 'description',
        'author', 'developer', 'version', 'censorship', 'language',
        'platform', 'release_date', 'download_link', 'buy_link',
        'system_requirements', 'gallery_images', 'download_content', 'password', 'installation_guide',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'release_date' => 'date',
        'gallery_images' => 'array',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
