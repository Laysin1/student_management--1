@extends('layouts.teacher')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Scores Report</h1>
                <p class="text-gray-600 mt-2">View and analyze student scores</p>
            </div>
            <a href="{{ route('teacher.classes.scores') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition shadow-md">
                ← Back to Scores
            </a>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex gap-0 mb-8 border-b border-gray-300 bg-white rounded-t-lg">
            <a href="{{ route('teacher.classes.index') }}" class="px-6 py-4 font-semibold text-gray-700 hover:text-blue-600 transition">
                📚 Classes
            </a>
            <a href="{{ route('teacher.classes.attend') }}" class="px-6 py-4 font-semibold text-gray-700 hover:text-blue-600 transition">
                📋 Attendance
            </a>
            <a href="{{ route('teacher.classes.scores') }}" class="px-6 py-4 font-semibold text-gray-700 hover:text-blue-600 transition">
                ✏️ Scores
            </a>
            <a href="{{ route('teacher.classes.scoresReport') }}" class="px-6 py-4 font-semibold text-blue-600 border-b-4 border-blue-600">
                📊 Report
            </a>
        </div>

        <!-- Filter Form -->
        <form method="GET" id="filterForm" class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Class Selection -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        📚 Class <span class="text-red-500">*</span>
                    </label>
                    <select name="class_id" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 bg-white cursor-pointer" required onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Select Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                Grade {{ $class->grade_level }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Type Selection -->
                <div>
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        📊 Type <span class="text-red-500">*</span>
                    </label>
                    <select name="report_type" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 bg-white cursor-pointer" required onchange="handleTypeChange()">
                        <option value="">-- Select Type --</option>
                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                        <option value="semester" {{ request('report_type') == 'semester' ? 'selected' : '' }}>Semester</option>
                        <option value="final" {{ request('report_type') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>

                <!-- Month Selection (shows only for Monthly) -->
                <div id="monthDiv" class="hidden">
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        📅 Month <span class="text-red-500">*</span>
                    </label>
                    <select name="month" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 bg-white cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- All Months --</option>
                        <option value="1" {{ request('month') == 1 ? 'selected' : '' }}>January</option>
                        <option value="2" {{ request('month') == 2 ? 'selected' : '' }}>February</option>
                        <option value="3" {{ request('month') == 3 ? 'selected' : '' }}>March</option>
                        <option value="4" {{ request('month') == 4 ? 'selected' : '' }}>April</option>
                        <option value="5" {{ request('month') == 5 ? 'selected' : '' }}>May</option>
                        <option value="6" {{ request('month') == 6 ? 'selected' : '' }}>June</option>
                        <option value="7" {{ request('month') == 7 ? 'selected' : '' }}>July</option>
                        <option value="8" {{ request('month') == 8 ? 'selected' : '' }}>August</option>
                        <option value="9" {{ request('month') == 9 ? 'selected' : '' }}>September</option>
                        <option value="10" {{ request('month') == 10 ? 'selected' : '' }}>October</option>
                        <option value="11" {{ request('month') == 11 ? 'selected' : '' }}>November</option>
                        <option value="12" {{ request('month') == 12 ? 'selected' : '' }}>December</option>
                    </select>
                </div>

                <!-- Semester Selection (shows only for Semester/Final) -->
                <div id="semesterDiv" class="hidden">
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        🎓 Period <span class="text-red-500">*</span>
                    </label>
                    <select name="semester" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 bg-white cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Select --</option>
                        <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1st Semester</option>
                        <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2nd Semester</option>
                        <option value="final" {{ request('semester') == 'final' ? 'selected' : '' }}>Final</option>
                    </select>
                </div>
            </div>
        </form>

        <script>
            function handleTypeChange() {
                const typeSelect = document.querySelector('select[name="report_type"]');
                const monthDiv = document.getElementById('monthDiv');
                const semesterDiv = document.getElementById('semesterDiv');

                if (typeSelect.value === 'monthly') {
                    monthDiv.classList.remove('hidden');
                    semesterDiv.classList.add('hidden');
                } else if (typeSelect.value === 'semester' || typeSelect.value === 'final') {
                    monthDiv.classList.add('hidden');
                    semesterDiv.classList.remove('hidden');
                } else {
                    monthDiv.classList.add('hidden');
                    semesterDiv.classList.add('hidden');
                }
            }

            // Initialize on page load
            handleTypeChange();
        </script>

        <!-- Scores Report Table -->
        @if(request('class_id') && ((request('report_type') == 'monthly' && request('month')) || (request('report_type') == 'semester' && request('semester')) || (request('report_type') == 'final' && request('semester'))))
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                    <h3 class="text-2xl font-bold text-white">
                        📊
                        @if(request('report_type') == 'monthly')
                            {{ date('F Y', mktime(0, 0, 0, request('month'), 1)) }} - Monthly Scores
                        @elseif(request('report_type') == 'semester' && request('semester') == '1')
                            1st Semester Scores
                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                            2nd Semester Scores
                        @elseif(request('report_type') == 'final')
                            Final Scores
                        @endif
                    </h3>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-gray-100 to-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Student Name</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">ID</th>
                                <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Email</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Gender</th>
                                <th class="px-6 py-4 text-center text-sm font-bold text-blue-600">Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($scoreRecords as $index => $record)
                                <tr class="border-b hover:bg-blue-50 transition {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $record->student->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $record->student->student_id ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $record->student->user->email ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600 text-center">{{ ucfirst($record->student->gender ?? 'N/A') }}</td>
                                    <td class="px-6 py-4 text-center text-sm font-bold">
                                        @if(request('report_type') == 'semester' && request('semester') == '1')
                                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full">{{ $record->first_semester ?? '—' }}</span>
                                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full">{{ $record->second_semester ?? '—' }}</span>
                                        @else
                                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full">{{ $record->final_score ?? '—' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6"/>
                                        </svg>
                                        <p class="text-gray-500 font-semibold">No score records found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($scoreRecords->count() > 0)
                    <div class="px-6 py-4 border-t bg-gray-50">
                        {{ $scoreRecords->links() }}
                    </div>
                @endif
            </div>

        @elseif(request('class_id'))
            <!-- Empty State - Select Type -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-600 font-semibold text-lg mt-4">Select a type and period above to view the report</p>
            </div>

        @else
            <!-- Empty State - Select Class -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
                <p class="text-gray-600 font-semibold text-lg mt-4">Select a class to get started</p>
            </div>
        @endif
    </div>
</div>
@endsection
