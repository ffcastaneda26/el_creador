<div class="max-w-3xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">

        {{-- Producto --}}
        <div class="flex flex-col">
            <div>
                <label for="purchase_detail_id"
                    class="block text-sm font-medium text-gray-700">{{ __('Product') }}
                </label>
            </div>
            <div class="w-full">
                <select wire:model="purchase_detail_id" wire:click="read_purchase_item" id="purchase_detail_id"
                    class="mt-1 block w-full border rounded p-2" {{ $can_edit_receipt ? '' : 'disabled' }}>
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($purchase_details as $purchase_detail)
                        <option value="{{ $purchase_detail->id }}">
                            {{ $purchase_detail->product->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-col md:items-end">
            <div class="flex flex-row gap-2 mb-2">

                {{-- Cantidad --}}
                <div class="flex flex-col">
                    <label for="receipt_quantity"
                        class="block text-sm font-medium text-gray-700">{{ __('Quantity') }}</label>
                    <input wire:model="receipt_quantity"
                        type="number" min="1"
                        max="{{ $max_receipt_quantity }}"
                        class="mt-1 block w-20 border rounded p-2"
                        {{ $can_edit_receipt ? '' : 'disabled' }}
                        required
                    >

                    @error('receipt_quantity')
                        <div class="text-md text-red-500">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Costo --}}
                <div class="flex flex-col">
                    <label for="receipt_cost"
                        class="block text-sm font-medium text-gray-700">{{ __('Cost') }}</label>
                    <input type="text" wire:model="receipt_cost"
                        class="mt-1 block w-20 border rounded p-2"
                        {{ $can_edit_receipt ? '' : 'disabled' }} required
                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                        onblur="if (!/^\d+(\.\d{1,2})?$/.test(this.value)) { this.value = parseFloat(this.value).toFixed(2); } else if (this.value === '') { this.value = '0.00' }">
                    @error('receipt_cost')
                        <div class="text-md text-red-500">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex flex-col justify-end">
                    <x-button class="ms-3 bg-green-400 hover:bg-green-800"
                        wire:click="store_receipt_detail({{ $receipt_detail_id }})"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove
                            wire:target="store_receipt_detail({{ $receipt_detail_id }})"
                            {{ $can_edit_receipt ? '' : 'disabled' }}>
                            {{ $product_in_receipt ? __('Update') : __('Add') }}
                        </span>
                        <span wire:loading wire:target="store_receipt_detail({{ $receipt_detail_id }})">
                            {{ __('Processing') }}
                        </span>
                    </x-button>
                </div>
            </div>
        </div>

    </div>
</div>
