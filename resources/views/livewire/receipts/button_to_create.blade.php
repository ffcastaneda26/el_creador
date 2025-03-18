<div>
    <button wire:click="create_receipt"
        class="w-50 {{ $can_create_receipt ? 'bg-green-500 hover:bg-green-700 text-white' : 'bg-white text-red-500' }}  rounded-md p-2 text-center text-sm"
        {{ $can_create_receipt ? '': 'disabled' }}>
        {{ $can_create_receipt  ? __('Create') . ' ' . __('Material Receipt')
                                : __('There are no Purchase Orders with Products Pending Fulfillment')
        }}
    </button>
</div>
