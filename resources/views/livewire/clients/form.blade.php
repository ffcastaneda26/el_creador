<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="5xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('New Client') }}
        </div>
    </x-slot>
    <form wire:submit="store">
        <x-slot name="content">
            {{-- <x-validation-errors></x-validation-errors> --}}
            @include('livewire.clients.form_content')
        </x-slot>

        <x-slot name="footer">
            <div class="flex jutify-between items-center gap-10">
                <div>
                    <x-danger-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
                        {{ __('Cancel') }}
                    </x-danger-button>
                </div>

                <div>
                    <x-button class="ms-3 bg-green-400 hover:bg-green-800" 
                            wire:click="store" 
                            wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="store">
                                {{ __('Save') }}
                            </span>
                            <span wire:loading wire:target="store">
                                {{__('Processing')}}
                            </span>
                    </x-button>
                </div>
                
            </div>
        </x-slot>
    </form>
</x-confirmation-modal>