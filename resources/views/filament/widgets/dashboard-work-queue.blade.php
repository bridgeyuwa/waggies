<x-filament-widgets::widget>
    <x-filament::section heading="Work queue" description="Start with the items that need a staff decision.">
        <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4">
            @foreach($queues as $queue)
                <a href="{{ $queue['href'] }}" class="group rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-primary-300 hover:shadow-md dark:border-white/10 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-3">
                        <p class="text-sm font-semibold text-gray-950 dark:text-white">{{ $queue['label'] }}</p>
                        <span class="text-2xl font-bold text-gray-950 dark:text-white">{{ $queue['count'] }}</span>
                    </div>
                    <p class="mt-2 text-xs leading-5 text-gray-600 dark:text-gray-400">{{ $queue['description'] }}</p>
                    <span class="mt-3 inline-flex min-h-11 items-center text-sm font-semibold text-primary-600 group-hover:text-primary-500">Open queue <span aria-hidden="true" class="ml-1">→</span></span>
                </a>
            @endforeach
        </div>

        <div class="mt-6 border-t border-gray-200 pt-5 dark:border-white/10">
            <h3 class="text-sm font-semibold text-gray-950 dark:text-white">Quick actions</h3>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach($actions as $action)
                    <a href="{{ $action['href'] }}" class="inline-flex min-h-11 items-center rounded-lg border border-gray-300 px-3 text-sm font-medium text-gray-700 transition hover:border-primary-400 hover:text-primary-600 dark:border-white/15 dark:text-gray-300 dark:hover:border-primary-500 dark:hover:text-primary-400">{{ $action['label'] }}</a>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
