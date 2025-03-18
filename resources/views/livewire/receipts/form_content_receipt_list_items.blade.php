<div class="max-w-3xl mx-auto">
    <div class="w-full">

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead
                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="bg-gray-300 text-center ">
                        <th scope="col" class="px-4 py-3">{{ __('Product') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Quantity') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Cost') }}</th>
                        <th scope="col" class="px-4 py-3">{{ __('Amount') }}</th>
                        <th colspan="2" class="px-4 py-3" {{ $can_edit_receipt ? '' : 'hidden' }}>
                            {{ __('Actions') }} </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($receipt_details as $receipt_detail)
                        <tr class="py-2 border-b dark:border-gray-700 hover:bg-gray-100">
                            <td class="py-2">{{ $receipt_detail->product->name }}</td>
                            <td class="text-end py-2">{{ $receipt_detail->quantity }}</td>
                            <td class="text-end py-2">
                                {{ number_format($receipt_detail->cost, 2, '.', ',') }}
                            </td>

                            <td class="text-end px-4 py-2">
                                {{ number_format(round($receipt_detail->quantity * $receipt_detail->cost, 2), 2, '.', ',') }}
                            </td>
                            <td class="py-2" colspan="2" {{ $can_edit_receipt ? '' : 'hidden' }}>
                                <button wire:click="read_receipt_item({{ $receipt_detail->id }})"
                                    {{ $can_edit_receipt ? '' : 'disabled' }}
                                    class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 text-xs rounded">
                                    {{ __('Edit') }}
                                </button>
                                <button wire:click="destroy_receipt_detail({{ $receipt_detail->id }})"
                                    {{ $can_edit_receipt ? '' : 'disabled' }}
                                    class="bg-red-500 hover:bg-red-200 text-white font-bold py-1 px-2 text-xs rounded">
                                    {{ __('Delete') }}
                                </button>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
