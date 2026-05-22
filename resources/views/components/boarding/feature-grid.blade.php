{{--
    Feature card grid for boarding pages.

    Props:
      $features — array of ['icon', 'title', 'desc']
      $columns — sm grid cols class suffix (default 3)
--}}
@props([
    'features' => [],
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
    @foreach ($features as $item)
        <div class="bg-surface-purple rounded-2xl p-6 flex gap-4 hover:shadow-soft transition-shadow">
            <div class="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary icon-filled"
                    aria-hidden="true">{{ $item['icon'] }}</span>
            </div>
            <div>
                <h3 class="font-semibold text-primary-dark mb-1">{{ $item['title'] }}</h3>
                <p class="text-sm text-primary-dark/60 leading-relaxed">{{ $item['desc'] }}</p>
            </div>
        </div>
    @endforeach
</div>
