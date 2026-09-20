@props(['variant' => 'default'])
<x-waggies.alert type="warning" {{ $attributes->class([$variant === 'compact' ? 'p-3' : 'p-4 sm:p-5']) }}>
    <p class="{{ $variant === 'compact' ? 'text-xs' : 'text-sm font-medium' }} text-amber-900">This tool provides general information only and is not a substitute for professional veterinary diagnosis or treatment. If your pet is in distress, contact your vet or an emergency animal clinic immediately.</p>
</x-waggies.alert>
