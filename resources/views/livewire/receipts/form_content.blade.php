<div class="w-full">
    <div class="overflow-x-auto">
        <div class="p-4 max-w-3xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
                <div class="flex flex-row justify-around items-center gap-4 mb-2">
                    {{-- Orden de compra --}}
                    <div>
                        <label for="purchase_id"
                            class="block text-sm font-medium text-gray-700">{{ __('Purchase Order') }}</label>
                        <select wire:model="purchase_id"
                            {{-- wire:change="read_purchase"  --}}
                            id="purchase_id"
                            class="mt-1 block w-auto border rounded p-2" required>
                            <option value="">{{ __('Select') }}</option>
                            @if ($purchases)
                                @foreach ($purchases as $purchase)
                                    <option value="{{ $purchase->id }}">{{ $purchase->folio }}</option>
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
                            class="block text-sm font-medium text-gray-700">{{ __('Folio') }}</label>
                        <input type="text" wire:model="folio" id="folio" placeholder="Folio"
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
                            class="block text-sm font-medium text-gray-700">{{ __('Date') }}</label>
                        <input type="date" wire:model="date" id="date"
                            class="mt-1 block w-full border rounded p-2"
                            max="{{ $max_date }}"
                            required>

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
                    {{-- Importe --}}
                    <div>
                        <label for="amount"
                            class="block text-sm font-medium text-gray-700">{{ __('Amount') }}</label>
                        <input type="text" wire:model="amount" id="amount" placeholder="0.00"
                            class="mt-1 block w-24 border rounded p-2" required
                            oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"
                            onblur="if (!/^\d+(\.\d{1,2})?$/.test(this.value)) { this.value = parseFloat(this.value).toFixed(2); }">
                        @error('amount')
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
        @if ($purchase_details)
            <div class="p-4 max-w-3xl mx-auto">
                <div class="p-2 max-w-3xl mx-auto">
                    {{ __('Pending Purchase Order Items') }}
                </div>
                <div class="flex flex-col">
                    <table class="divide-y divide-gray-200 dark:divide-neutral-700">
                        <thead>
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                    {{ __('Product') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                    {{ __('Quantity') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                    {{ __('Received') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                    {{ __('Pending') }}</th>
                                <th scope="col"
                                    class="px-6 py-3 text-start text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                                    {{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purchase_details as $purchase_detail)
                                <tr
                                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                                        {{ $purchase_detail->product->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ $purchase_detail->quantity }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ $purchase_detail->quantity_received }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                                        {{ $purchase_detail->quantity - $purchase_detail->quantity_received }}</td>

                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                        <x-button wire:click="edit({{ $purchase_detail->id }})"
                                            class="h-6 w-auto text-white bg-orange-500 hover:bg-orange-700 text-center">
                                            {{ __('Edit') }}
                                        </x-button>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
