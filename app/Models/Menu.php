<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'url',
        'icon',
        'order',
        'is_active',
        'open_in_new_tab',
        'type',
        'parent_id',
        'depth',
        'custom_attributes',
        'description',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'open_in_new_tab' => 'boolean',
        'order' => 'integer',
        'depth' => 'integer',
        'custom_attributes' => 'array',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeHeader($query)
    {
        return $query->where('type', 'header');
    }

    public function scopeFooter($query)
    {
        return $query->where('type', 'footer');
    }

    public function scopeSidebar($query)
    {
        return $query->where('type', 'sidebar');
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    // Accessors
    public function getFullUrlAttribute(): string
    {
        if (filter_var($this->url, FILTER_VALIDATE_URL)) {
            return $this->url;
        }

        return url($this->url);
    }

    public function getTargetAttribute(): string
    {
        return $this->open_in_new_tab ? '_blank' : '_self';
    }

    public function getRelAttribute(): string
    {
        return $this->open_in_new_tab ? 'noopener noreferrer' : '';
    }

    // Methods
    public function isChild(): bool
    {
        return !is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function getBreadcrumb(): array
    {
        $breadcrumb = [];
        $menu = $this;

        while ($menu) {
            $breadcrumb[] = [
                'name' => $menu->name,
                'url' => $menu->full_url,
            ];
            $menu = $menu->parent;
        }

        return array_reverse($breadcrumb);
    }
}
