@props(['title' => null, 'headerAction' => null])

<div {{ $attributes->merge(['class' => 'card app-card']) }}>
    @if($title || $headerAction)
    <div class="card-header app-card-header d-flex justify-content-between align-items-center">
        @if($title)
        <h5 class="card-title mb-0">{{ $title }}</h5>
        @endif
        @if($headerAction)
        <div class="card-actions">
            {{ $headerAction }}
        </div>
        @endif
    </div>
    @endif

    <div class="card-body app-card-body">
        {{ $slot }}
    </div>
</div>
