<x-layouts.front :title="$title ?? 'Product Title'">
    <div>

        <img src="{{ $product->image_url}}" alt="{{ $product->name }}" class="rounded-lg shadow" />
        <div>
            <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
            <p>{{ $product->description }}</p>
        </div>
    </div>

</x-layouts.front>
