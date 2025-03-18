<div>
    <div class="w-80 space-y-3">
        <input type="text" wire:model.live.debounce.150ms="search"
            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 dark:focus:ring-neutral-600"
            placeholder="{{ __('Search') . ' ' . __('Folio') . ' ' . __('Or') . ' ' . __('Reference') }}">
    </div>
</div>
