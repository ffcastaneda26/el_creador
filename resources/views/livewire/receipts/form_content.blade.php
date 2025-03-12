<div class="w-full">
    <div class="overflow-x-auto">
        {{-- <form wire:submit.prevent="create_receipt"> --}}
            <div class="p-4 max-w-3xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="purchase_id"
                            class="block text-sm font-medium text-gray-700">{{ __('Purchase Order') }}</label>
                        <select wire:model="purchase_id" wire:change="read_purchase" id="purchase_id"
                            class="mt-1 block w-full border rounded p-2" required>
                            <option value="">{{ __('Select') }}</option>
                            @foreach ($purchases as $purchase)
                                <option value="{{ $purchase->id }}">{{ $purchase->folio }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="folio"
                            class="block text-sm font-medium text-gray-700">{{ __('Folio') }}</label>
                        <input type="text"
                            wire:model="folio" id="folio" placeholder="Folio"
                            class="mt-1 block w-full border rounded p-2" required>
                    </div>

                    <div>
                        <label for="date"
                            class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                        <input type="date" wire:model="date" id="date"
                            class="mt-1 block w-full border rounded p-2" required>
                    </div>
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea wire:model="notes" id="notes" placeholder="Notas" class="mt-1 block w-full border rounded p-2 mb-4"></textarea>
                </div>

                <div class="flex justify-end">
                    <x-button wire:click="create_receipt" class="bg-green-500 text-white px-4 py-2 rounded">
                        {{ __('Create') }}

                    </x-button>
                    <button  wire:click="create_receipt"
                    class="bg-blue-500 hover:bg-black text-white font-bold py-2 px-4 rounded">
                        {{ __('Create') }}
                    </button>
                </div>

        {{-- </form> --}}

    </div>
</div>
