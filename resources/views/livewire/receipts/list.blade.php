<div class="overflow-hidden mt-2">
    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
        <thead>
            <tr>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Folio') }}</th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Purchase') }}</th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Date') }}</th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Reference') }}</th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Amount') }}</th>
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Status') }}</th>
                <th colspan="3"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">
                    {{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $record)
                <tr
                    class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">
                        {{ $record->purchase_id }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ $record->folio }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ $record->date->format('d-M-Y') }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ $record->reference }}</td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">
                        {{ $record->amount }}</td>

                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 w-auto text-center">
                        <span
                            class="
                            @if ($record->status->getColor() === 'success') bg-green-500
                            @elseif ($record->status->getColor() === 'warning')
                                bg-yellow-500
                            @elseif ($record->status->getColor() === 'primary')
                                bg-blue-500
                            @elseif ($record->status->getColor() === 'indigo')
                                bg-indigo-500
                            @elseif ($record->status->getColor() === 'danger')
                                bg-red-500 @endif">
                            <i class="{{ $record->status->getFontAwasomeIcon() }} mr-2"
                                aria-hidden="true"></i>{{ $record->status->getLabel() }}
                        </span>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                        <button wire:click="edit({{ $record->id }})"
                            class="h-auto w-auto  bg-orange-500 hover:bg-orange-700  text-center inline-flex items-center px-4 py-2  dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest dark:hover:bg-white dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150">
                            {{ $record->status->getLabel() === 'Abierto' ? __('Edit') : __('View') }}
                        </button>


                    </td>

                    <td>
                        <button wire:click="receive_receipt({{ $record->id }})"
                            wire:loading.attr="disabled"
                            class="h-auto w-auto  bg-blue-500 hover:bg-black  text-center inline-flex items-center px-4 py-2  dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest dark:hover:bg-white dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
                            {{ $record->checkAmount() ? '' : 'disabled' }}
                            {{ $record->status->getLabel() === 'Abierto' ? '' : 'disabled' }}>
                            <span wire:loading.remove
                                wire:target="receive_receipt({{ $record->id }})">


                                @if ($record->checkAmount())
                                    @if ($record->status->getLabel() == 'Abierto')
                                        {{ __('Receive') }}
                                    @else
                                        {{ $record->status->getLabel() }}
                                    @endif
                                @else
                                    @if ($record->has_details())
                                        {{ __('Verify Amount') }}
                                    @else
                                        {{ __('Add Items') }}
                                    @endif
                                @endif
                            </span>

                            <span wire:loading
                                wire:target="receive_receipt({{ $record->id }})">
                                {{ __('Receiving Material') }}
                            </span>

                        </button>
                    </td>


                    <td>
                        <button wire:click="destroy({{ $record->id }})"
                            class="h-auto w-auto  bg-red-500 hover:bg-orange-700  text-center inline-flex items-center px-4 py-2  dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest dark:hover:bg-white dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
                            {{ $record->status->getLabel() === 'Abierto' ? '' : 'disabled' }}>
                            {{ __('Delete') }}
                        </button>
                    </td>


                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-2xl text-center text-red-500">
                        {{ __('No records found') }}</td>
                </tr>
            @endforelse

        </tbody>
    </table>
</div>
<div class="mt-4">
    {{ $records->links() }}
</div>
