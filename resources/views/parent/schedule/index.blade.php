@extends('layouts.parent')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">Class Schedule</h1>

        @php
            $parent = DB::table('parent_users')
                ->where('user_id', Auth::user()->id)
                ->first();

            if (!$parent) {
                abort(403, 'Parent profile not found');
            }

            $studentIds = DB::table('parent_student')
                ->where('parent_user_id', $parent->id)
                ->pluck('student_id');

            $classIds = DB::table('students')
                ->whereIn('id', $studentIds)
                ->pluck('class_id');

            $schedules = DB::table('schedules')
                ->whereIn('class_id', $classIds)
                ->orderBy('day_of_week')
                ->get();
        @endphp

        @if(count($schedules) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                @endphp

                @foreach($days as $day)
                    @php
                        $daySchedules = $schedules->filter(fn($s) => $s->day_of_week === $day);
                    @endphp

                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ $day }}</h3>

                        @if(count($daySchedules) > 0)
                            <div class="space-y-3">
                                @foreach($daySchedules as $schedule)
                                    <div class="border border-gray-200 rounded p-3">
                                        <p class="text-sm font-semibold text-gray-900">{{ $schedule->subject ?? 'Subject' }}</p>
                                        <p class="text-xs text-gray-600">{{ $schedule->start_time ?? '—' }} - {{ $schedule->end_time ?? '—' }}</p>
                                        <p class="text-xs text-gray-600">Room: {{ $schedule->room ?? '—' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No classes scheduled</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <p class="text-gray-500">No schedule available for your children.</p>
            </div>
        @endif
    </div>
</div>
@endsection
