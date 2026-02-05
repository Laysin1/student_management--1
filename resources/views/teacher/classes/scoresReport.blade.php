@extends('layouts.teacher')

@section('content')
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-2xl text-gray-800">Student Scores Report</h2>
                <a href="{{ route('teacher.classes.scores') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    ← Go Back
                </a>
            </div>        <!-- Class + Month + Semester Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
                    <select name="class_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="this.form.submit()">
                        <option value="">-- Choose a Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                Grade {{ $class->grade_level }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select name="report_type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="this.form.submit()">
                        <option value="">-- All Reports --</option>
                        <option value="monthly" {{ request('report_type') == 'monthly' ? 'selected' : '' }}>Monthly Scores</option>
                        <option value="semester" {{ request('report_type') == 'semester' ? 'selected' : '' }}>Semester Scores</option>
                        <option value="final" {{ request('report_type') == 'final' ? 'selected' : '' }}>Final Scores</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Month</label>
                    <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="this.form.submit()">
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
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Semester</label>
                    <select name="semester" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="this.form.submit()">
                        <option value="">-- All --</option>
                        <option value="1" {{ request('semester') == 1 ? 'selected' : '' }}>1st Semester</option>
                        <option value="2" {{ request('semester') == 2 ? 'selected' : '' }}>2nd Semester</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Scores Report Table -->
        @if(request('class_id'))
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        @if(request('report_type') == 'semester' && request('semester') == '1')
                            1st Semester Scores
                            @if(request('month'))
                                - {{ date('F', mktime(0, 0, 0, request('month'), 1)) }}
                            @endif
                        @elseif(request('report_type') == 'semester' && request('semester') == '2')
                            2nd Semester Scores
                            @if(request('month'))
                                - {{ date('F', mktime(0, 0, 0, request('month'), 1)) }}
                            @endif
                        @elseif(request('report_type') == 'final')
                            Final Scores
                            @if(request('month'))
                                - {{ date('F', mktime(0, 0, 0, request('month'), 1)) }}
                            @endif
                        @elseif(request('report_type') == 'monthly')
                            Monthly Scores
                            @if(request('month'))
                                - {{ date('F', mktime(0, 0, 0, request('month'), 1)) }}
                            @endif
                        @else
                            All Scores
                        @endif
                    </h3>
                </div>

                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Gender</th>
                            <th class="px-6 py-3 text-center text-sm font-semibold text-gray-700">Score</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($scoreRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $record->student->student_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $record->student->user->email ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ ucfirst($record->student->gender ?? 'N/A') }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-600 font-semibold">
                                    @if(request('report_type') == 'semester' && request('semester') == '1')
                                        {{ $record->first_semester ?? '—' }}
                                    @elseif(request('report_type') == 'semester' && request('semester') == '2')
                                        {{ $record->second_semester ?? '—' }}
                                    @else
                                        {{ $record->final_score ?? '—' }}
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No score records found for the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $scoreRecords->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">Select a class to view scores report</p>
            </div>
        @endif
    </div>
</div>
@endsection
