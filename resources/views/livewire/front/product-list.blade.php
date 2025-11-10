<x-layouts.front :title="$title ?? 'Product Page'">
    <div>
        <h1 class="text-2xl font-semibold mb-4">All Products</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
                    <img src="{{ $product->imageSize(460, 192) }}" alt="{{ $product->name }}"
                        class="rounded-md w-full h-48 object-cover" />
                    <h2 class="text-lg font-semibold mt-3">{{ $product->name }}</h2>
                    <p class="text-gray-600 text-sm mt-1">{{ $product->price }}</p>
                    <a href="{{ route('front.store', $product->slug) }}"
                        class="inline-block mt-3 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">View
                        Details</a>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.front>
