<div class="max-w-3xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
        <div class="flex flex-row justify-around items-center gap-4 mb-2">
            {{-- Orden de compra --}}
            <div>
                <label for="purchase_id"
                    class="block text-sm font-medium text-gray-700">{{ __('Purchase Order') }}</label>
                @if ($can_edit_receipt)
                    <select wire:model="purchase_id" id="purchase_id" class="mt-1 block w-full border rounded p-2"
                        {{ $lock_purchase_id_on_edit ? 'disabled' : '' }}
                        {{ $can_edit_receipt ? '' : 'disabled' }}
                        {{ $purchases->count() == 1 ? 'disabled' : '' }}
                        required>
                        <option value="">{{ __('Select') }}</option>
                        @if ($purchases)
                            @foreach ($purchases as $purchase)
                                <option value="{{ $purchase->id }}"
                                    {{ $purchase_id == $purchase->id ? 'selected' : '' }}
                                    {{ $purchases->count() == 1 ? 'selected' : '' }}
                                >
                                    {{ $purchase->folio }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                @else
                    <input type="text" disabled value={{ $purchase_id }}>
                @endif

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
                    class="mt-1 block w-20 border rounded p-2" {{ $can_edit_receipt ? '' : 'disabled' }}
                    required>
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
                    class="mt-1 block w-full border rounded p-2" max="{{ $max_date }}"
                    {{ $can_edit_receipt ? '' : 'disabled' }} required>

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
                    class="mt-1 block w-full border rounded p-2" maxlength="30"
                    {{ $can_edit_receipt ? '' : 'disabled' }} required>
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
                    placeholder="0.00" class="mt-1 block w-full border rounded p-2"
                    {{ $can_edit_receipt ? '' : 'disabled' }} required
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
            <textarea wire:model="notes" id="notes" placeholder="Notas" class="mt-1 block w-full border rounded p-2 mb-2"
                {{ $can_edit_receipt ? '' : 'disabled' }}></textarea>
        </div>
    </div>
</div>
