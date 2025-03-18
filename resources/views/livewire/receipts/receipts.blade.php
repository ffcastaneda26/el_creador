<div class="container">
    {{-- Listado de clientes --}}

    <div>{{ __('Material Receptions') }}</div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <div class="p-1.5 min-w-full inline-block align-middle">
                        <div class="flex justify-between items-center mb-2 ">
                            {{-- Bucar recepción x folio o referencia --}}
                            @include('livewire.receipts.search')
                            {{-- Botón para crear --}}
                            @include('livewire.receipts.button_to_create')
                        </div>

                        @include('livewire.receipts.list')
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario Modal --}}
    @include('livewire.receipts.form')
</div>
