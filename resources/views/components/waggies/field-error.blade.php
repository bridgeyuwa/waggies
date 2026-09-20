@props(['message' => null, 'id' => null])

@if($message)
    <p @if($id) id="{{ $id }}" @endif role="alert" class="text-xs font-medium text-error">{{ $message }}</p>
@endif
