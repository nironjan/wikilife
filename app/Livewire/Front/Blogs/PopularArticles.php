<?php

namespace App\Livewire\Front\Blogs;

use App\Models\BlogPost;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class PopularArticles extends Component
{
    public $popularArticles;
    public $limit = 5;
    public $showRanking = true;
    public $title = 'Popular Articles';
    public $showImages = true;

    public function mount($limit = 5, $showRanking = true, $title = null, $showImages = true)
    {
        $this->limit = $limit;
        $this->showRanking = $showRanking;
        $this->title = $title ?: 'Popular Articles';
        $this->showImages = $showImages;

        $this->loadPopularArticles();
    }

    public function placeholder(){
        return view('livewire.front.blogs.popular-article-skeleton');
    }

    public function loadPopularArticles()
    {
        $this->popularArticles = BlogPost::published()
            ->with(['blogCategory', 'author'])
            ->orderBy('views', 'desc')
            ->take($this->limit)
            ->get();
    }

    public function refresh()
    {
        $this->loadPopularArticles();
    }


    public function render()
    {
        return view('livewire.front.blogs.popular-articles');
    }
}
