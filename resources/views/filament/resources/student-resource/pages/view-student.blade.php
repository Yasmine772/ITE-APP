<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Personal Data --}}
        <x-filament::card class="bg-blue-50">
            <h2 class="text-xl font-bold mb-4 text-blue-700">Personal Data</h2>
            <div class="space-y-2">
                <p><strong>Name:</strong> {{ $record->name }}</p>
                <p><strong>Email:</strong> {{ $record->email }}</p>
                <p><strong>Bio:</strong> {{ $record->bio ?? 'غير محدد' }}</p>
            </div>
        </x-filament::card>

        {{-- Academic Information --}}
        <x-filament::card class="bg-green-50">
            <h2 class="text-xl font-bold mb-4 text-green-700">Academic Information</h2>
            <div class="space-y-2">
                <p><strong>Academic Qualification:</strong> {{ $record->academic_qualification ?? 'غير محدد' }}</p>
                <p><strong>Years of Experience:</strong> {{ $record->years_of_experience ?? 'غير محدد' }}</p>
                <p><strong>Degree:</strong> {{ $record->degree ?? 'غير محدد' }}</p>
            </div>
        </x-filament::card>

    </div>
</x-filament::page>
