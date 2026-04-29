@extends('layouts.app')

@section('title','Mood Calendar')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mood_calendar.css') }}">
@endpush

@section('content')
<div class="calendar-wrapper">
    <h1 class="page-title">Daily Mood Tracker</h1>

    <div class="calendar-container">
        <div class="left-panel">
            <a href="{{ route('mood.calendar',['month'=>now()->format('Y-m')]) }}" class="today-label">
                ☀️ Kembali ke Hari Ini
            </a>

            <div class="mood-card">
                <h3 style="color: #4A3A35;">📝 Mood Entry</h3>
                <p>Bagaimana perasaanmu?</p>

                <form action="{{ route('mood.calendar.store') }}" method="POST">
                    @csrf
                    <button type="submit" name="mood" value="awesome" class="mood-btn awesome">
                        😍 Awesome
                    </button>
                    <button type="submit" name="mood" value="good" class="mood-btn good">
                        😊 Good
                    </button>
                    <button type="submit" name="mood" value="neutral" class="mood-btn neutral">
                        😐 Neutral
                    </button>
                    <button type="submit" name="mood" value="bad" class="mood-btn bad">
                        😔 Bad
                    </button>
                    <button type="submit" name="mood" value="terrible" class="mood-btn terrible">
                        😣 Terrible
                    </button>
                </form>
            </div>
        </div>

        <div class="right-panel">
            <div class="calendar-box">
                <div class="month-nav">
                    <a href="?month={{ $date->copy()->subMonth()->format('Y-m') }}" class="nav-arrow">
                        <i class="fa-solid fa-chevron-left"></i> ←
                    </a>
                    <h2>{{ $date->format('F Y') }}</h2>
                    <a href="?month={{ $date->copy()->addMonth()->format('Y-m') }}" class="nav-arrow">
                        → <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <div class="calendar-grid">
                    {{-- Header Hari --}}
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="day-header">{{ $day }}</div>
                    @endforeach

                    @php
                        $startDay = $date->copy()->startOfMonth()->dayOfWeek;
                        $daysInMonth = $date->daysInMonth;
                        $todayStr = now()->format('Y-m-d');
                    @endphp

                    {{-- Empty cell sebelum tanggal 1 --}}
                    @for($i=0; $i<$startDay; $i++)
                        <div class="calendar-cell" style="opacity: 0.3;"></div>
                    @endfor

                    {{-- Loop tanggal --}}
                    @for($day=1; $day<=$daysInMonth; $day++)
                        @php
                            $currentDate = $date->format('Y-m-').str_pad($day, 2, '0', STR_PAD_LEFT);
                            $isToday = ($currentDate === $todayStr);
                            $mood = $moods[$currentDate] ?? null;

                            $emojiMap = [
                                'awesome'  => '😍',
                                'good'     => '😊',
                                'neutral'  => '😐',
                                'bad'      => '😔',
                                'terrible' => '😣'
                            ];
                        @endphp

                        <div class="calendar-cell {{ $isToday ? 'is-today' : '' }}">
                            <div class="date-number">{{ $day }}</div>

                            @if($mood)
                                <div class="mood-event {{ $mood->mood }}">
                                    <div class="event-time">
                                        {{ \Carbon\Carbon::parse($mood->created_at)->format('H:i') }}
                                    </div>
                                    <div class="event-mood">
                                        {{ $emojiMap[$mood->mood] ?? '' }} {{ ucfirst($mood->mood) }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection  