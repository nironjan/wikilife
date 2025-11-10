<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class RecentCard extends Component
{
    public $limit = 8;

    public function placeholder(){
        return view('livewire.front.blogs.blog-card-skeleton');
    }

    public function render()
    {
        $recentPosts = BlogPost::with('blogCategory')
        ->published()
        ->orderBy('published_at', 'desc')
        ->take($this->limit)
        ->get();

    // Temporary debug - remove this later
    if (app()->environment('local')) {
        logger('Recent Posts Count: ' . $recentPosts->count());
        logger('Recent Posts: ' . $recentPosts->pluck('title'));
    }

    return view('livewire.front.blogs.recent-card', [
        'recentPosts' => $recentPosts
    ]);
    }
}
