<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

        <div class="bg-white p-4 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Number of Students</h3>
            <canvas id="studentsChart"></canvas>
        </div>


        <div class="bg-white p-4 rounded-2xl shadow">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Number of Teachers</h3>
            <canvas id="teachersChart"></canvas>
        </div>
    </div>


    <div class="mt-6 bg-white p-4 rounded-2xl shadow w-full">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Last advertisements added</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 w-full">
                <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Title</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Date</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                @foreach($this->getLatestAdvertisements() as $ad)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $ad->title }}</td>
                        <td class="px-4 py-2 text-sm text-gray-600">{{ $ad->created_at->format('d/m/Y') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @php
        $studentData = $this->getStudentCountsPerYear();
        $teacherData = $this->getTeacherCountsPerYear();
    @endphp
    <script>

        new Chart(document.getElementById('studentsChart'), {
            type: 'line',
            data: {
                labels: @json($studentData['years']),
                datasets: [{
                    label: 'Number of Students',
                    data: @json($studentData['counts']),
                    borderColor: 'rgb(34,197,94)',
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    fill: true
                }]
            },
            options: { responsive: true }
        });


        new Chart(document.getElementById('teachersChart'), {
            type: 'line',
            data: {
                labels: @json($teacherData['years']),
                datasets: [{
                    label: 'Number of Teachers',
                    data: @json($teacherData['counts']),
                    borderColor: 'rgb(59,130,246)',
                    backgroundColor: 'rgba(59,130,246,0.2)',
                    fill: true
                }]
            },
            options: { responsive: true }
        });
    </script>

</x-filament::page>
