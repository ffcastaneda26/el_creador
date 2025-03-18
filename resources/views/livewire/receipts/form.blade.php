<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="3xl" showIcon='no'>

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ $record_id ?  __('Material Receipt') .': ' . $folio : __('New Material Receipt') }}
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
            @include('livewire.receipts.form_footer')
        </x-slot>
    </form>
</x-confirmation-modal>
