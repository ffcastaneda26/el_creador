<div class="container">
    {{-- Listado de clientes --}}

    <div>{{ __('Material Receptions') }}</div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex flex-col">
                    <div class="-m-1.5 overflow-x-auto">
                        <div class="p-1.5 min-w-full inline-block align-middle">
                            <div class="flex justify-between items-center mb-2 ">
                                <div>
                                    <div class="w-80 space-y-3">
                                        <input type="text"
                                                wire:model.live.debounce.150ms="search"
                                                class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
                                                    placeholder="{{ __('Search')  .' ' . __('Folio') }}"
                                                >
                                    </div>
                                </div>
                                <div>
                                    <x-button wire:click="create_receipt"
                                            class="w-50 bg-green-500 hover:bg-green-700 text-white rounded-md p-2 text-center">
                                            {{ __('Create') . ' ' . __('Material Receipt')}}
                                    </x-button>
                                </div>

                            </div>

                                <div class="overflow-hidden mt-2">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-neutral-700">
                                        <thead>
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Folio') }}</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Purchase') }}</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Date') }}</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Reference') }}</th>
                                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Amount') }}</th>
                                                <th  colspan="2" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase dark:text-neutral-500">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($records as $record )
                                                <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-neutral-900 dark:even:bg-neutral-800">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-neutral-200">{{ $record->purchase_id }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$record->folio}}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$record->date->format('d-M-Y')}}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$record->reference}}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-neutral-200">{{$record->amount}}</td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                        <x-button wire:click="edit({{ $record->id }})"
                                                            class="h-6 w-auto text-white bg-orange-500 hover:bg-orange-700 text-center">
                                                            {{ __('Edit') }}
                                                        </x-button>

                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                        <x-button wire:click="destroy({{ $record->id }})"
                                                            class="h-6 w-auto text-white bg-red-500 hover:bg-orange-700 text-center">
                                                            {{ __('Delete') }}
                                                        </x-button>

                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-2xl text-center text-red-500">{{ __('No records found') }}</td>
                                                </tr>
                                            @endforelse

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $records->links() }}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Formulario Modal --}}
    @include('livewire.receipts.form')
</div>
