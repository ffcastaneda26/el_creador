 
<tr class="border-b dark:border-gray-700 hover:bg-gray-100">
    <td class="">{{ $ware_request_detail->product->name }}</td>
    <td class="text-end">{{ $ware_request_detail->quantity }}</td>
    <td class="text-end">{{ $stock_available }}</td>
    <td class="text-end">{{ $ware_request_detail->quantity_delivered }}</td>
    <td class="text-end px-4">{{ $ware_request_detail->quantity - $ware_request_detail->quantity_delivered }}</td>
    <td class=" text-white w-auto">
        <span class=" @if ($ware_request_detail->status->getColor() === 'success') bg-green-500
                    @elseif ($ware_request_detail->status->getColor() === 'warning') bg-yellow-500
                    @elseif ($ware_request_detail->status->getColor() === 'primary') bg-blue-500
                    @elseif ($ware_request_detail->status->getColor() === 'indigo') bg-indigo-500
                    @elseif ($ware_request_detail->status->getColor() === 'danger') bg-red-500 @endif">
                    <i class="{{ $ware_request_detail->status->getFontAwasomeIcon() }} mr-2"></i>
                    {{ $ware_request_detail->status->getLabel() }}
        </span>
    </td>

    
    <td class="px-6 2 whitespace-nowrap text-end text-sm font-medium">
        <input wire:model="quantity"
            wire:change="validate_quantity" 
            type="number"
            class="{{ $quantity_error ? 'bg-red-500 border-red-700' : 'bg-gray-50 border-gray-300' }} border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            required min="1"
            max="{{ $max_quantity }}"
            {{ $ware_request_detail->hasPending() ? '' : 'disabled' }}
        />

    </td>
    <td class="px-6 2 whitespace-nowrap text-center text-sm font-medium">
        @if($quantity <=  $max_quantity &&  $quantity > 0)
            <x-button wire:click="updateItem()"
                    class="h-6 w-auto text-white bg-blue-300 hover:bg-orange-700 text-center {{ $quantity > 0  ? '' : 'hidden'}}" 
                    :disabled="!$ware_request_detail->hasPending()"
                    :disabled="$quantity >  $max_quantity">
                    {{   __('Suply')  }}
            </x-button>
        @else
            <label for="" class="text-red-500 font-extrabold {{ $quantity > 0 ? '' : 'hidden' }}">{{ __('Verify') }}</label>
        @endif

    </td>

</tr>
