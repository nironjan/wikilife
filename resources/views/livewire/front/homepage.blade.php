<div>
    <main>
        <!-- Hero Section -->
        <livewire:partials.hero-section lazy="on-load" />

        <!-- Born Today Section -->
        <livewire:front.born-today.card lazy="on-load" />

        <!-- Popular Person Section -->
        <livewire:front.popular-person.index lazy="on-load" />

        <!-- Call to Action Section -->
        <livewire:partials.call-to-action />

        <!-- Born Today Section -->
        <livewire:front.blogs.recent-card lazy="on-load" />

        <!-- Recent Persons & Sidebar Section -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Main Content -->
                <div class="md:col-span-8">
                    <livewire:partials.recent-person-list />
                </div>

                <!-- Sidebar -->
                <div class="md:col-span-4">
                    <livewire:partials.profession-category-list />
                </div>
            </div>
        </div>

    </main>


</div>
