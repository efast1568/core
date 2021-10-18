<x-waterhole::action-buttons
    :for="$for"
    :only="$only"
    :exclude="$exclude"
    :button-attributes="['class' => 'menu-item', 'role' => 'menuitem']"
>
    <x-slot name="before">
        <ui-popup {{ $attributes->merge(['placement' => 'bottom-start']) }}>
            @if (isset($button))
                {{ $button }}
            @else
                <button {{ (new Illuminate\View\ComponentAttributeBag($buttonAttributes))->class('btn btn--icon btn--transparent btn--small') }}>
                    <x-waterhole::icon icon="heroicon-o-dots-horizontal"/>
                </button>
            @endif
            <ui-menu class="menu" hidden>
    </x-slot>

    <x-slot name="after">
            </ui-menu>
        </ui-popup>
    </x-slot>
</x-waterhole::action-buttons>
