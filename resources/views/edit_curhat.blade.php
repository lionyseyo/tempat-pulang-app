@extends('layouts.app')

@section('title', 'Edit Cerita')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/edit_curhat.css') }}">
@endpush

@section('content')

@php
    $mood = strtolower($curhat->mood ?? 'default');
    $badgeClass = "mood-$mood";
@endphp

<div class="edit-container">

    <div class="edit-header">
        <h1>📖 Edit Cerita</h1>
        <p>Perbaiki ceritamu dengan tenang. Tidak perlu sempurna ✨</p>
    </div>

    <div class="edit-card">

        <div class="edit-top">
            <div class="mood-badge {{ $badgeClass }}">
                {{ $curhat->emoji ?? '💭' }}
                {{ ucfirst($curhat->mood ?? 'Mood') }}
            </div>

            <div class="edit-date">
                {{ \Carbon\Carbon::parse($curhat->created_at)->format('d M Y • H:i') }}
            </div>
        </div>

        <form action="{{ route('curhat.update', $curhat->id) }}" method="POST">
            @csrf
            @method('PUT')




            
            <textarea name="content" placeholder="Tulis ulang isi hatimu...">{{ old('content', $curhat->content) }}</textarea>

            @error('content')
                <div class="error-text">{{ $message }}</div>
            @enderror

            <div class="edit-actions">
                <button type="submit" class="btn-save">
                    💾 Simpan Perubahan
                </button>

                <a href="{{ route('curhat.index') }}" class="btn-cancel">
                    Batal
                </a>
            </div>
        </form>

    </div>

</div>

@endsection