<div class="w-full">
    <div class="overflow-x-auto">
        <div class="max-w-3xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
                <div class="flex flex-row justify-around items-center gap-4 mb-2">
                    {{-- Orden de compra --}}
                    <div>
                        <label for="purchase_id"
                            class="block text-sm font-medium text-gray-700">{{ __('Purchase Order') }}</label>
                        <select wire:model="purchase_id" id="purchase_id" class="mt-1 block w-full border rounded p-2"
                            {{ $lock_purchase_id_on_edit ? 'disabled' : '' }} required>
                            <option value="">{{ __('Select') }}</option>
                            @if ($purchases)
                                @foreach ($purchases as $purchase)
                                    <option value="{{ $purchase->id }}"
                                        {{ $purchase_id == $purchase->id ? 'selected' : '' }}>{{ $purchase->folio }}
                                    </option>
                                @endforeach
                            @endif
                        </select>

                        @error('purchase_id')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- Folio --}}
                    <div>
                        <label for="folio"
                            class="block w-full text-sm font-medium text-gray-700">{{ __('Folio') }}</label>
                        <input type="number" min="1" wire:model="folio" id="folio" placeholder="Folio"
                            class="mt-1 block w-20 border rounded p-2" required>
                        @error('folio')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="date"
                            class="block w-full text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                        <input type="date" wire:model="date" id="date"
                            class="mt-1 block w-full border rounded p-2" max="{{ $max_date }}" required>

                        @error('date')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Referencia --}}
                    <div>
                        <label for="reference"
                            class="block text-sm font-medium text-gray-700">{{ __('Reference') }}</label>
                        <input type="text" wire:model="reference" id="reference" placeholder="{{ __('Reference') }}"
                            class="mt-1 block w-full border rounded p-2" maxlength="30" required>
                        @error('reference')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                </div>
                <div class="flex flex-row justify-between items-center gap-6">

                    {{-- Importe --}}
                    <div>
                        <label for="amount"
                            class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                        <input type="text" wire:model="amount" wire:keyup ="calculateTaxAndTotal" id="amount"
                            placeholder="0.00" class="mt-1 block w-full border rounded p-2" required
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                            onblur="if (!/^\d+(\.\d{1,2})?$/.test(this.value)) { this.value = parseFloat(this.value).toFixed(2); } : '0.00'">
                        @error('amount')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- IVA --}}
                    <div>
                        <label for="tax"
                            class="block text-sm font-medium text-gray-700">{{ __('Tax') }}</label>
                        <input type="text" wire:model="tax" id="tax" placeholder="0.00"
                            class="mt-1 block w-full border rounded p-2 text-right" required disabled>
                        @error('tax')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    {{-- TOTAL --}}
                    <div>
                        <label for="tax"
                            class="block text-sm font-medium text-gray-700">{{ __('Total') }}</label>
                        <input type="text" wire:model="total" id="total" placeholder="0.00"
                            class="mt-1 block w-full border rounded p-2 text-right" required disabled
                            style="align-content: end">
                        @error('total')
                            <div class="text-md text-red-500">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700">{{ __('Notes') }}</label>
                    <textarea wire:model="notes" id="notes" placeholder="Notas" class="mt-1 block w-full border rounded p-2 mb-2"></textarea>
                </div>
            </div>
        </div>

        @if ($record_id && $purchase_details)
            <div class="max-w-3xl mx-auto">
                <div class="flex flex-column gap-4 mb-2">
                    <label for="purchase_detail_id">{{ __('Product') }}:</label>

                    <select wire:model="purchase_detail_id" wire:click="read_purchase_item" id="purchase_detail_id"
                        class="mt-1 block w-1/2 border rounded p-2">
                        <option value="">{{ __('Select') }}</option>
                        @foreach ($purchase_details as $purchase_detail)
                            <option value="{{ $purchase_detail->id }}">
                                {{ $purchase_detail->product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @if ($purchase_detail_id)
                    <div>
                        <div class="flex flex-row justify-between gap-2">
                            <div>{{ $receipt_product_id }}</div>
                            <div>{{ $receipt_product_name }}</div>
                            <div>
                                <input wire:model="receipt_quantity"
                                    type="number"
                                    min="1"
                                    max="{{ $max_receipt_quantity }}"
                                    class="mt-1 block w-20 border rounded p-2"
                                    required>
                                @error('receipt_quantity')
                                    <div class="text-md text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div>
                                <input type="text"
                                    wire:model="receipt_cost"
                                    class="mt-1 block w-20 border rounded p-2"
                                    required
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                                    onblur="if (!/^\d+(\.\d{1,2})?$/.test(this.value)) { this.value = parseFloat(this.value).toFixed(2); } : '0.00'">
                                @error('receipt_cost')
                                    <div class="text-md text-red-500">
                                        {{ $message }}
                                    </div>
                                @enderror


                            </div>
                            <div>

                                <x-button class="ms-3 bg-green-400 hover:bg-green-800" wire:click="store_receipt_detail"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="store_receipt_detail">
                                        {{ $product_in_receipt ? __('Update') : __('Save') }}
                                    </span>
                                    <span wire:loading wire:target="store_receipt_detail">
                                        {{ __('Processing') }}
                                    </span>
                                </x-button>

                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

    </div>
</div>
