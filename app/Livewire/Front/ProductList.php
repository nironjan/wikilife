<?php

namespace App\Livewire\Front;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.front')]
class ProductList extends Component
{
    public $products;



    public function mount()
    {
        $this->products = Product::with('images')->latest()->get();
    }



    public function render()
    {
        return view('livewire.front.product-list')
            ->layoutData([
                'title' => 'All Products',
            ]);
    }
}
