<x-filament::page>
    <div class="flex justify-end mb-4">
        @if($this->isEditing)
            <x-filament::button wire:click="save" color="success">Save</x-filament::button>
            <x-filament::button wire:click="toggleEdit" color="secondary">Cancel</x-filament::button>
        @else
            <x-filament::button wire:click="toggleEdit">Edit</x-filament::button>
        @endif
    </div>
    {{ $this->form }}
</x-filament::page>
