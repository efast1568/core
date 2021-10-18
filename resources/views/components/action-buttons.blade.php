@php
    $actionable = Waterhole\Extend\Actionable::getActionable($for);
    $actions = collect(Waterhole\Extend\Action::for([$for]))
        ->filter(fn($action) => ! $action->hidden);

    if (isset($only)) {
        $actions = $actions->filter(fn($action) => in_array(get_class($action), $only));
    }

    if (isset($exclude)) {
        $actions = $actions->filter(fn($action) => ! in_array(get_class($action), $exclude));
    }
@endphp

@if (count($actions))
    {{ $before ?? '' }}

    <form action="{{ route('waterhole.action.store') }}" method="POST" {{ $attributes }}>
        @csrf
        <input type="hidden" name="actionable" value="{{ $actionable }}">
        <input type="hidden" name="id[]" value="{{ $for->id }}">

        @foreach ($actions as $action)
            {{ $action->render(collect([$for]), new Illuminate\View\ComponentAttributeBag($buttonAttributes)) }}
        @endforeach
    </form>

    {{ $after ?? '' }}
@endif
