<?php

namespace App\Livewire\Partials;

use App\Models\People;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class HeroSection extends Component
{
    public $search = '';
    public $trendingPeople = [];
    public $searchSuggestions = [];

    public function placeholder(){
        return view('livewire.partials.hero-section-skeleton');
    }




    public function render()
    {
        return view('livewire.partials.hero-section');
    }
}
