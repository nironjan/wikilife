<?php

namespace App\Livewire\Front;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.front')]
class ProductDetails extends Component
{
    public $product;
    public function mount($slug)
    {
        $this->product = Product::where('slug', $slug)->firstOrFail();
    }
    public function render()
    {
        return view('livewire.front.product-details')
            ->layoutData([
                'title' => $this->product->name,
            ]);
        ;
    }
}
