<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="3xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('New Material Receipt') }}
        </div>
    </x-slot>
    <form wire:submit="store">
        <x-slot name="content">
            {{-- <x-validation-errors></x-validation-errors> --}}
            <div class="container">
                @include('livewire.receipts.form_content')

            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex jutify-between items-center gap-10">
                <div>
                    <x-danger-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                        {{ $record_id ? __('Close')  : __('Cancel') }}
                    </x-danger-button>
                </div>

                <div>
                    <x-button class="ms-3 bg-green-400 hover:bg-green-800"
                            wire:click="store_receipt"
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="store_receipt">
                                {{ $record_id ? __('Update')  : __('Save') }}
                            </span>
                            <span wire:loading wire:target="store_receipt">
                                {{__('Processing')}}
                            </span>
                    </x-button>
                </div>

            </div>
        </x-slot>
    </form>
</x-confirmation-modal>
