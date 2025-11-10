<?php

namespace App\Livewire\Partials;

use Livewire\Component;

class CallToAction extends Component
{
    public $stats = [
        'biographies' => '10,000+',
        'professions' => '50+',
        'updated' => 'Daily'
    ];

    public $features = [
        [
            'icon' => 'verified',
            'title' => 'Verified Profiles',
            'description' => 'All biographies are thoroughly researched and fact-checked'
        ],
        [
            'icon' => 'comprehensive',
            'title' => 'Comprehensive Content',
            'description' => 'Detailed life stories, achievements, and personal journeys'
        ],
        [
            'icon' => 'updated',
            'title' => 'Regularly Updated',
            'description' => 'Fresh content added daily with latest information'
        ]
    ];

    public function explorePeople()
    {
        return redirect()->route('people.people.index');
    }

    public function contribute()
    {
        // You can redirect to a contribution page or show a modal
        return redirect()->route('contribute');
    }

    public function render()
    {
        return view('livewire.partials.call-to-action');
    }
}
