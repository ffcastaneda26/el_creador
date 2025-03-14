        @if ($purchase_details)
            <div class="max-w-3xl mx-auto">
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
                            @foreach ($purchase_details as $purchase_detail)
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
