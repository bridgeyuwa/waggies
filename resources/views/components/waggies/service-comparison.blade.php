@props(['services', 'features'])

<section class="bg-white py-16">
    <div class="page-container">
        <div class="mb-10 text-center">
            <span class="text-eyebrow mb-4 block">Compare Services</span>
            <h2 class="font-serif text-3xl font-bold leading-tight text-primary-dark md:text-4xl">Find the Right Fit for Your Pet</h2>
            <p class="mx-auto mt-3 max-w-2xl text-primary-dark/60">See which services include the features you care about most - at a glance.</p>
        </div>

        <div class="hidden lg:block">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[640px] border-collapse">
                    <thead>
                        <tr>
                            <th class="sticky left-0 z-10 min-w-[140px] bg-white p-4 text-left text-sm font-semibold text-primary-dark">Feature</th>
                            @foreach ($services as $service)
                                <th class="min-w-[120px] p-4 text-center"><div class="flex flex-col items-center gap-1"><x-waggies.icon name="{{ $service['icon'] }}" size="20" class="text-primary" /><span class="text-sm font-semibold text-primary-dark">{{ $service['label'] }}</span><span class="text-xs text-primary-dark/50">{{ $service['price'] }}</span></div></th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($features as $index => $feature)
                            <tr class="border-t border-primary/10 {{ $index % 2 === 1 ? 'bg-surface-purple/30' : '' }}">
                                <td class="sticky left-0 z-10 bg-white p-4 text-sm font-medium text-primary-dark">{{ $feature['label'] }}</td>
                                @foreach ($services as $service)
                                    <td class="p-4 text-center">@if(in_array($service['key'], $feature['supported'], true))<x-waggies.icon name="check-circle" size="20" variant="filled" class="text-primary" />@else<x-waggies.icon name="error" size="20" class="text-primary-dark/20" />@endif</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 lg:hidden sm:grid-cols-2">
            @foreach ($services as $service)
                <x-waggies.card class="p-5">
                    <div class="mb-4 flex items-center gap-3"><div class="flex h-10 w-10 items-center justify-center rounded-xl bg-surface-purple"><x-waggies.icon name="{{ $service['icon'] }}" size="20" class="text-primary" /></div><div><p class="text-sm font-semibold text-primary-dark">{{ $service['label'] }}</p><p class="text-xs text-primary-dark/50">{{ $service['price'] }}</p></div></div>
                    <ul class="space-y-2.5">
                        @foreach ($features as $feature)
                            @php($supported = in_array($service['key'], $feature['supported'], true))
                            <li class="flex items-center gap-2 text-sm">@if($supported)<x-waggies.icon name="check-circle" size="20" variant="filled" class="text-primary" />@else<x-waggies.icon name="error" size="20" class="text-primary-dark/20" />@endif<span class="{{ $supported ? 'text-primary-dark' : 'text-primary-dark/60' }}">{{ $feature['label'] }}</span></li>
                        @endforeach
                    </ul>
                </x-waggies.card>
            @endforeach
        </div>
    </div>
</section>
