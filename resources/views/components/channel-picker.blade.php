<div {{ $attributes }} class="field">
    @if ($value)
        <input type="hidden" name="channel_id" value="{{ $value }}">
    @endif

    <ui-popup placement="bottom-start">
        <button class="btn" type="button">
            @if ($selectedChannel)
                <x-waterhole::channel-label :channel="$selectedChannel"/>
            @else
                <span>{{ __('waterhole::forum.channel-picker-placeholder') }}</span>
            @endif
            <x-waterhole::icon icon="tabler-chevron-down"/>
        </button>

        <ui-menu class="menu channel-picker__menu" hidden>
            @foreach ($channels as $channel)
                <button
                    type="submit"
                    name="{{ $name }}"
                    value="{{ $channel->id }}"
                    class="menu-item @if ($channel->id == $value) is-active @endif"
                    role="menuitemradio"
                >
                    <x-waterhole::icon :icon="$channel->icon"/>
                    <span>
                        <span class="menu-item__title">{{ $channel->name }}</span>
                        <span class="menu-item__description">{{ $channel->description }}</span>
                    </span>
                    @if ($channel->id == $value)
                        <x-waterhole::icon icon="tabler-check" class="menu-item__check"/>
                    @endif
                </button>
            @endforeach
        </ui-menu>
    </ui-popup>

    @if ($instructions = $selectedChannel?->instructions_html)
        <div class="rounded p-lg bg-attention-light content">
            {{ Waterhole\emojify($instructions) }}
        </div>
    @endif
</div>
