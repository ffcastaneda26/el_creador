<div class="w-full">

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr class="bg-gray-300 text-center ">
                    <th scope="col" class="px-4 py-3">{{ __('Product') }}</th>
                    <th scope="col" class="px-4 py-3">{{ __('Quantity') }}</th>
                    <th scope="col" class="px-4 py-3">{{ __('Delivered') }}</th>
                    <th scope="col" class="px-4 py-3">{{ __('Pending') }}</th>
                    <th scope="col" class="px-4 py-3">{{ __('Status') }}</th>
                    <th scope="col" class="px-4 py-3">{{ __('Suply') }}</th>
                    <th scope="col" class="px-4 py-3">Actions </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($warehouse_request_details as $ware_request_detail)
                <livewire:warehouse-requests.ware-house-request-detail :$ware_request_detail :key="$ware_request_detail->id" />
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $records->links() }}
    </div>

</div>
