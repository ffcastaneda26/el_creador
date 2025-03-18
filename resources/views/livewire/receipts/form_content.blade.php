<div class="w-full">
    <div class="overflow-x-auto">
        @include('livewire.receipts.form_receipt_head')


        @if ($record_id && $purchase_details)
            @include('livewire.receipts.form_content_detail_item')
        @endif



        {{-- Si la recepción tiene partidas --}}
        @if ($receipt_details)
            @include('livewire.receipts.form_content_receipt_list_items')
        @endif

    </div>
</div>
