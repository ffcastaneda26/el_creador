<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="5xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('Supply items from the request No.') . '  '. $warehouse_request_record->folio }}
        </div>
    </x-slot>


    <form wire:submit="store">
        <x-slot name="content">
            @include('livewire.warehouse-requests.form_content')
        </x-slot>

        <x-slot name="footer">
            {{-- <div class="flex jutify-between items-center gap-10">
                <div>
                    <x-danger-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-danger-button>
                </div>

            </div> --}}
        </x-slot>
    </form>
</x-confirmation-modal>
