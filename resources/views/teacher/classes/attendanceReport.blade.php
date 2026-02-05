@extends('layouts.teacher')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-semibold text-2xl text-gray-800">Daily Attendance Report</h2>
            <a href="{{ route('teacher.classes.attend') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                ← Go Back
            </a>
        </div>

        <!-- Class + Date + Status Filter -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Class</label>
                    <select name="class_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- Choose a Class --</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->grade_level }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Date</label>
                    <input type="date" name="attendance_date" value="{{ request('attendance_date') }}" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="document.getElementById('filterForm').submit()">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="document.getElementById('filterForm').submit()">
                        <option value="">-- All Status --</option>
                        <option value="present" {{ request('status') == 'present' ? 'selected' : '' }}>Present</option>
                        <option value="absent" {{ request('status') == 'absent' ? 'selected' : '' }}>Absent</option>
                        <option value="permission" {{ request('status') == 'permission' ? 'selected' : '' }}>Permission</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quick Filter</label>
                    <select name="quick_filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 w-full" onchange="applyQuickFilter(this.value)">
                        <option value="">-- Select --</option>
                        <option value="today">Today</option>
                        <option value="yesterday">Yesterday</option>
                        <option value="last_7_days">Last 7 Days</option>
                        <option value="last_month">Last Month</option>
                        <option value="this_month">This Month</option>
                    </select>
                </div>
            </form>
        </div>

        <!-- Attendance Report Table -->
        @if(request('class_id'))
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Student ID</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Date</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($attendanceRecords as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $record->student->user->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $record->student->student_id ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $record->attendance_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-sm">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $record->status === 'present' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $record->status === 'absent' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $record->status === 'permission' ? 'bg-blue-100 text-blue-800' : '' }}
                                    ">
                                        {{ ucfirst($record->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $record->remarks ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $attendanceRecords->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">Select a class to view attendance report</p>
            </div>
        @endif
    </div>
</div>

<script>
    function applyQuickFilter(filterType) {
        const form = document.getElementById('filterForm');
        const today = new Date();
        let dateValue;

        switch(filterType) {
            case 'today':
                dateValue = today.toISOString().split('T')[0];
                break;
            case 'yesterday':
                const yesterday = new Date(today);
                yesterday.setDate(yesterday.getDate() - 1);
                dateValue = yesterday.toISOString().split('T')[0];
                break;
            case 'last_7_days':
                const sevenDaysAgo = new Date(today);
                sevenDaysAgo.setDate(sevenDaysAgo.getDate() - 7);
                dateValue = sevenDaysAgo.toISOString().split('T')[0];
                break;
            case 'last_month':
                const lastMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                dateValue = lastMonthStart.toISOString().split('T')[0];
                break;
            case 'this_month':
                const thisMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
                dateValue = thisMonthStart.toISOString().split('T')[0];
                break;
            default:
                return;
        }

        document.querySelector('input[name="attendance_date"]').value = dateValue;
        form.submit();
    }
</script>
@endsection
