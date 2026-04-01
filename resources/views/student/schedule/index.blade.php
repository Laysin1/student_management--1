@extends('layouts.student')

@section('content')
<div class="py-8">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">
            Schedule
        </h2>

        <!-- Schedule Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if(auth()->user()->student->class && auth()->user()->student->class->schedule)
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ auth()->user()->student->class->name }}</h3>

                    @if(auth()->user()->student->class->schedule->photo_path)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . auth()->user()->student->class->schedule->photo_path) }}"
                                 alt="Schedule" class="w-full h-80 object-cover rounded-lg border border-gray-200">
                        </div>
                    @endif

                    <div class="space-y-3">
                        @if(auth()->user()->student->class->schedule->day_of_week)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700"><strong>Day:</strong> {{ ucfirst(auth()->user()->student->class->schedule->day_of_week) }}</span>
                            </div>
                        @endif

                        @if(auth()->user()->student->class->schedule->start_time)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00-.293.707l-2.829 2.829a1 1 0 101.415 1.415L9 11.414V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-gray-700"><strong>Time:</strong> {{ \Carbon\Carbon::parse(auth()->user()->student->class->schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse(auth()->user()->student->class->schedule->end_time)->format('h:i A') }}</span>
                            </div>
                        @endif

                        @if(auth()->user()->student->class->schedule->room)
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                                </svg>
                                <span class="text-gray-700"><strong>Room:</strong> {{ auth()->user()->student->class->schedule->room }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Schedule</h3>
                    <p class="text-gray-500">Your schedule will appear here once it's uploaded by the admin.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
