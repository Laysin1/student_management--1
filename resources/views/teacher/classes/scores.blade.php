@extends('layouts.teacher')

@section('content')
<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Score Management</h1>
            <p class="text-gray-600 mt-2">Enter and manage student scores efficiently</p>
        </div>

        <!-- Tabs Navigation -->
        <div class="flex gap-0 mb-8 border-b border-gray-300 bg-white rounded-t-lg">
            <a href="{{ route('teacher.classes.index') }}" class="px-6 py-4 font-semibold text-gray-700 hover:text-blue-600 transition">
                📚 Classes
            </a>
            <a href="{{ route('teacher.classes.attend') }}" class="px-6 py-4 font-semibold text-gray-700 hover:text-blue-600 transition">
                📋 Attendance
            </a>
            <a href="{{ route('teacher.classes.scores') }}" class="px-6 py-4 font-semibold text-blue-600 border-b-4 border-blue-600">
                ✏️ Scores
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
                    </select>
                </div>

                <!-- Month Selection (shows only for Monthly) -->
                <div id="monthDiv" class="hidden">
                    <label class="block text-sm font-bold text-gray-900 mb-2">
                        📅 Month <span class="text-red-500">*</span>
                    </label>
                    <select name="month" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 font-medium text-gray-900 bg-white cursor-pointer" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Select Month --</option>
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

        <!-- Scores Input Section -->
        @if(request('class_id') && ((request('report_type') == 'monthly' && request('month')) || (request('report_type') == 'semester' && request('semester')) || (request('report_type') == 'final' && request('semester'))))
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-8 py-6">
                    <h3 class="text-3xl font-bold text-white">
                        📝
                        @if(request('report_type') == 'monthly')
                            {{ date('F Y', mktime(0, 0, 0, request('month'), 1)) }} - Monthly
                        @elseif(request('report_type') == 'semester' && request('semester') == '1')
                            1st Semester
                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                            2nd Semester
                        @elseif(request('report_type') == 'final')
                            Final Scores
                        @endif
                    </h3>
                </div>

                <!-- Score Entry Form -->
                <form method="POST" action="{{ route('teacher.classes.saveScores') }}" class="p-8" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                    <input type="hidden" name="month" value="{{ request('month') ?? '' }}">
                    <input type="hidden" name="report_type" value="{{ request('report_type') }}">
                    <input type="hidden" name="semester" value="{{ request('semester') ?? '' }}">
                    <input type="hidden" name="subject_id" value="1">

                    <!-- Responsive Score Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-gray-100 to-gray-50 border-b-2 border-gray-300">
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Student Name</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">ID</th>
                                    <th class="px-6 py-4 text-left text-sm font-bold text-gray-900">Email</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-gray-900">Gender</th>
                                    <th class="px-6 py-4 text-center text-sm font-bold text-blue-600">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($selectedClassStudents as $index => $student)
                                    <tr class="border-b hover:bg-blue-50 transition {{ $index % 2 == 0 ? 'bg-white' : 'bg-gray-50' }}">
                                        <td class="px-6 py-5 text-sm font-semibold text-gray-900">{{ $student->user->name ?? 'N/A' }}</td>
                                        <td class="px-6 py-5 text-sm text-gray-600">{{ $student->student_id ?? 'N/A' }}</td>
                                        <td class="px-6 py-5 text-sm text-gray-600">{{ $student->user->email ?? 'N/A' }}</td>
                                        <td class="px-6 py-5 text-sm text-gray-600 text-center">{{ ucfirst($student->gender ?? 'N/A') }}</td>
                                        <td class="px-6 py-5 text-center">
                                            @if(request('report_type') == 'semester' && request('semester') == '1')
                                                <input type="number" name="first_semester[{{ $student->id }}]" value="0" min="0" max="100" placeholder="0-100" class="w-24 px-3 py-3 mx-auto border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-bold text-lg transition bg-white hover:border-blue-400">
                                            @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                                <input type="number" name="second_semester[{{ $student->id }}]" value="0" min="0" max="100" placeholder="0-100" class="w-24 px-3 py-3 mx-auto border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-bold text-lg transition bg-white hover:border-blue-400">
                                            @else
                                                <input type="number" name="final_score[{{ $student->id }}]" value="0" min="0" max="100" placeholder="0-100" class="w-24 px-3 py-3 mx-auto border-2 border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-center font-bold text-lg transition bg-white hover:border-blue-400">
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m0 0h6"/>
                                            </svg>
                                            <p class="text-gray-500 font-semibold">No students found in this class</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-10 pt-8 border-t-2 border-gray-200 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-8 rounded-lg transition transform hover:scale-105 shadow-lg text-center text-lg">
                            💾 Save All Scores
                        </button>
                        <a href="{{ route('teacher.classes.scoresReport') }}" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-4 px-8 rounded-lg transition transform hover:scale-105 shadow-lg text-center text-lg">
                            📊 View Report
                        </a>
                        <a href="{{ route('teacher.classes.scores') }}" class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-4 px-8 rounded-lg transition transform hover:scale-105 shadow-lg text-center text-lg">
                            🔄 Clear
                        </a>
                    </div>
                </form>
            </div>

        @elseif(request('class_id'))
            <!-- Empty State - Select Type -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-20 h-20 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                <p class="text-gray-600 font-semibold text-lg mt-4">Select a type and period above to enter scores</p>
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
