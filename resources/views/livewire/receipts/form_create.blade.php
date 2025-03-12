<x-confirmation-modal wire:modelwire:model.live="showModal" maxWidth="5xl">

    <x-slot name="title">
        <div class="text-2xl font-bold text-center">
            {{ __('Create') . '  '. __('Material Receipt')}}
        </div>
    </x-slot>


    <form wire:submit="store">
        <x-slot name="content">
            <div class="fixed inset-0 overflow-y-auto flex items-center justify-center z-50">
                <div class="fixed inset-0 bg-black opacity-50"></div>
                <div class="relative bg-white w-full max-w-4xl p-6 rounded-lg shadow-xl z-10">
                   Formulario
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">

        </x-slot>
    </form>
</x-confirmation-modal>
