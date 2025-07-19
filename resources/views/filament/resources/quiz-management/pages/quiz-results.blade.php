<x-filament::page>
    <div class="space-y-6">
        <div class="p-6 bg-white rounded-xl shadow dark:bg-gray-800">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold tracking-tight">
                    {{ $this->getHeader() }}
                </h2>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ $this->getSubheading() }}
                </p>
            </div>
        </div>

        {{ $this->table }}
    </div>
</x-filament::page> 