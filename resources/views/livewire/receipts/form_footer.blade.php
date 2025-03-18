<div class="flex jutify-between items-center gap-10">
    <div>
        <x-danger-button wire:click="$toggle('showModal')" wire:loading.attr="disabled">
            {{ $record_id ? __('Close')  : __('Cancel') }}
        </x-danger-button>
    </div>

    <div>
        <x-button class="ms-3 bg-green-400 hover:bg-green-800 {{ $can_edit_receipt ? '' : 'hidden' }}"
                wire:click="store_receipt"
                wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="store_receipt">
                    {{ $record_id ? __('Update')  : __('Save') }}
                </span>
                <span wire:loading wire:target="store_receipt">
                    {{__('Processing')}}
                </span>
        </x-button>
    </div>

</div>
