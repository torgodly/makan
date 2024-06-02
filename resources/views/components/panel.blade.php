@props([
    'heading',
    'footer'
])

<div {{ $attributes->merge(['class' => 'bg-white border border-neutral-200 overflow-x-auto']) }}>
    @if(isset($heading))
        <div {{ $heading->attributes->class(['px-4 py-4']) }}>
            {{ $heading }}
        </div>
    @endif

    <div {{ isset($slot->attributes) ? $slot->attributes->class(['px-4 py-4 overflow-x-auto']) : '' }}>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div {{ $footer->attributes->class(['px-4 py-4 sm:px-6']) }}>
            {{ $footer }}
        </div>
    @endif
</div>
