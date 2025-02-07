<div class="container">
    {{-- Listado de Solicitudes de Almacén --}}
    <div>{{ __('Warehouse Requests') }}</div>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-col">
                <div class="-m-1.5 overflow-x-auto">
                    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
                        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
                            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                                <div
                                    class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-4">
                                    <div class="w-full md:w-1/2">
                                        <form class="flex items-center">
                                            <label for="simple-search" class="sr-only">{{ __('Search') }}</label>
                                            <div class="relative w-full">
                                                <div
                                                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                    <svg aria-hidden="true"
                                                        class="w-5 h-5 text-gray-500 dark:text-gray-400"
                                                        fill="currentColor" viewbox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </div>
                                                <input type="text" id="simple-search"
                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    placeholder="Search" required="">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr class="bg-gray-300 text-center ">
                                                <th scope="col" class="px-4 py-3">Folio</th>
                                                <th scope="col" class="px-4 py-3">{{ __('Date') }}</th>
                                                <th scope="col" class="px-4 py-3">{{ __('Reference') }}</th>
                                                <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                                                <th scope="col" class="px-4 py-3">Actions </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($records as $record)
                                                <tr class="border-b dark:border-gray-700 hover:bg-gray-100">
                                                    <td class="px-4 py-3">{{ $record->folio }}</td>
                                                    <td class="px-4 py-3">{{ $record->date->format('d-M-Y') }}</td>
                                                    <td class="px-4 py-3">{{ $record->reference }}</td>
                                                    <td class="px-4 py-3 text-white w-auto text-center">
                                                        <span class="
                                                            @if ($record->status->getColor() === 'success')
                                                                bg-green-500
                                                            @elseif ($record->status->getColor() === 'warning')
                                                                bg-yellow-500
                                                            @elseif ($record->status->getColor() === 'primary')
                                                                bg-blue-500
                                                            @elseif ($record->status->getColor() === 'indigo')
                                                                bg-indigo-500
                                                            @elseif ($record->status->getColor() === 'danger')
                                                                bg-red-500
                                                                @endif">
                                                                <i class="{{ $record->status->getFontAwasomeIcon() }} mr-2"
                                                                    aria-hidden="true"></i>{{ $record->status->getLabel() }}
                                                        </span>

                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                                        @if( $record->can_be_suply())
                                                            <button wire:click="suply({{ $record->id }})"
                                                                type="button" class="text-white w-24 h-10  bg-blue-800 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                <svg class="w-3.5 h-3.5 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 21">
                                                                <path d="M15 12a1 1 0 0 0 .962-.726l2-7A1 1 0 0 0 17 3H3.77L3.175.745A1 1 0 0 0 2.208 0H1a1 1 0 0 0 0 2h.438l.6 2.255v.019l2 7 .746 2.986A3 3 0 1 0 9 17a2.966 2.966 0 0 0-.184-1h2.368c-.118.32-.18.659-.184 1a3 3 0 1 0 3-3H6.78l-.5-2H15Z"/>
                                                                </svg>
                                                                {{  __('Suply')  }}
                                                            </button>
                                                        @else
                                                            @if($record->status == 'surtido')
                                                                <label class="text-green-400 font-extrabold">
                                                                    {{  __('Delivered') }}
                                                                </label>
                                                            @endif
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-4">
                                    {{ $records->links() }}
                                </div>

                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario Modal --}}
    @if ($warehouse_request_record)
        @include('livewire.warehouse-requests.form')
    @endif
</div>
