<?php

namespace App\Livewire\Partials;

use App\Models\People;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Lazy;
use Livewire\Component;

#[Lazy]
class ProfessionCategoryList extends Component
{
    public $categories;
    public $peopleCounts = [];

    protected $listeners = ['peopleUpdated' => 'refreshCounts'];

    public function mount()
    {
        $this->categories = config('professions.categories' , []);
        $this->loadPeopleCounts();
    }

    public function placeholder(){
        return view('livewire.partials.profession-category-skeleton');
    }

    protected function loadPeopleCounts()
    {
        foreach ($this->categories as $categoryKey => $category) {
            $professions = $category['professions'] ?? [];

            if (empty($professions)) {
                $this->peopleCounts[$categoryKey] = 0;
                continue;
            }

            $count = People::active()
                ->verified()
                ->where(function($query) use ($professions) {
                    foreach ($professions as $profession) {
                        $query->orWhereJsonContains('professions', strtolower($profession));
                    }
                })
                ->count();

            $this->peopleCounts[$categoryKey] = $count;
        }
    }



    public function refreshCounts(){
        $this->loadPeopleCounts();
    }



    public function render()
    {
        return view('livewire.partials.profession-category-list');
    }
}
