<?php

namespace App\Models;

use App\Services\SitemapService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(){
    // Generate sitemap when a blog category is created
    static::created(function ($category){
        if($category->is_active){
            app(SitemapService::class)->generateSitemap();
        }
    });

    // Generate sitemap when a blog category is updated
    static::updated(function ($category){
        if($category->isDirty(['is_active', 'slug', 'name'])){
            app(SitemapService::class)->generateSitemap();
        }
    });

    // Generate sitemap when a blog category is deleted
    static::deleted(function(){
        app(SitemapService::class)->generateSitemap();
    });
}

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
