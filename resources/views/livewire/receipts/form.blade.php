<x-confirmation-modal wire:modelwire:model.live="showFormCreate" maxWidth="3xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('Create') . '  '. __('Material Receipt')}}
        </div>
    </x-slot>


    <form wire:submit="store">
        <x-slot name="content">
            @include('livewire.receipts.form_content')

        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </form>
</x-confirmation-modal>
