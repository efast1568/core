<div {{ $attributes }} class="field">
    {{-- TODO: list using nav config --}}
{{--    <select name="{{ $name }}" class="input" data-action="channel-picker#update">--}}
{{--        @if ($allowNull)--}}
{{--            <option value="">Select a Channel</option>--}}
{{--        @endif--}}

{{--        @foreach (Waterhole\Models\Channel::all()->except($exclude) as $channel)--}}
{{--            @can('post', $channel)--}}
{{--                <option--}}
{{--                    value="{{ $channel->id }}"--}}
{{--                    {{ $value == $channel->id ? 'selected' : '' }}--}}
{{--                    title="{{ $channel->description }}"--}}
{{--                    data-instructions="{{ $channel->instructions }}"--}}
{{--                >--}}
{{--                    {{ $channel->display_name }}--}}
{{--                </option>--}}
{{--            @endcan--}}
{{--        @endforeach--}}
{{--    </select>--}}

{{--    <div--}}
{{--        class="alert alert--info"--}}
{{--        hidden--}}
{{--        data-channel-picker-target="instructions"--}}
{{--    >--}}
{{--        <div class="content" data-channel-picker-target="instructionsContent"></div>--}}
{{--    </div>--}}

    @if ($value)
        <input type="hidden" name="channel_id" value="{{ $value }}">
    @endif

    <ui-popup placement="bottom-start">
        <button class="btn" type="button">
            @if ($selectedChannel)
                <x-waterhole::channel-label :channel="$selectedChannel"/>
            @else
                <span>Select a Channel</span>
            @endif
            <x-waterhole::icon icon="heroicon-s-chevron-down"/>
        </button>

        <ui-menu class="menu channel-picker__menu" hidden>
            @foreach ($channels->except($exclude) as $channel)
                @can('post', $channel)
                    <button
                        type="submit"
                        name="{{ $name }}"
                        value="{{ $channel->id }}"
                        class="menu-item"
                        role="menuitemradio"
                    >
                        <x-waterhole::icon :icon="$channel->icon"/>
                        <span>
                            <span class="menu-item-title">{{ $channel->name }}</span>
                            <span class="menu-item-description">{{ $channel->description }}</span>
                        </span>
                        @if ($channel->id == $value)
                            <x-waterhole::icon icon="heroicon-o-check" class="menu-item-check"/>
                        @endif
                    </button>
                @endcan
            @endforeach
        </ui-menu>
    </ui-popup>

    @if ($selectedChannel?->instructions)
        <div class="alert alert--info content">
            {{ $selectedChannel->instructions }}
        </div>
    @endif
</div>
