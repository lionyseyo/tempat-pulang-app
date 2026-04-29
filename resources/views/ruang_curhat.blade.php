@extends('layouts.app')

@section('title', 'Ruang Curhat')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/ruang_curhat.css') }}">
@endpush

@section('content')
<div class="rc-wrapper">

    <div class="rc-header">
        <div>
            <h1>🌸 Ruang Curhat</h1>
            <p>Lihat perjalanan emosimu dari waktu ke waktu ✨</p>
        </div>
    </div>

    <div class="rc-top-filter">
        <button class="pill active" data-filter="all">Semua</button>
        <button class="pill blue" data-filter="sedih">😢 Sedih</button>
        <button class="pill green" data-filter="senang">😊 Senang</button>
        <button class="pill yellow" data-filter="cemas">😟 Cemas</button>
        <button class="pill red" data-filter="marah">😡 Marah</button>
        <button class="pill purple" data-filter="lelah">😴 Lelah</button>
    </div>

    <div class="rc-layout">
        <div class="rc-timeline">
            @forelse($curhats as $curhat)
                @php
                    $mood = strtolower($curhat->mood ?? '');
                    $moodColor = match($mood){
                        'senang' => 'green',
                        'sedih' => 'blue',
                        'cemas' => 'yellow',
                        'marah' => 'red',
                        'lelah' => 'purple',
                        default => 'pink'
                    };
                @endphp

                <div class="rc-item" data-mood="{{ $mood }}">
                    <div class="rc-date">
                        <div class="rc-day">{{ \Carbon\Carbon::parse($curhat->created_at)->format('d') }}</div>
                        <div class="rc-month">{{ \Carbon\Carbon::parse($curhat->created_at)->format('M') }}</div>
                    </div>

                    <div class="rc-card">
                        <div class="rc-card-top">
                            <span class="rc-badge {{ $moodColor }}">
                                {{ $curhat->emoji ?? '📝' }} {{ ucfirst($mood) }}
                            </span>
                            <span class="rc-time">
                                {{ \Carbon\Carbon::parse($curhat->created_at)->format('H:i') }} WIB
                            </span>
                        </div>

                        <div class="rc-content">
                            {{ $curhat->content }}
                        </div>

                        <div class="rc-actions">
                            <a href="{{ route('curhat.edit', $curhat->id) }}" class="rc-btn edit-btn">
                                <i class="fa-solid fa-pen-to-square"></i> Edit
                            </a>

                            <button type="button" class="rc-btn delete-btn open-delete-modal" data-id="{{ $curhat->id }}">
                                <i class="fa-solid fa-trash-can"></i> Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rc-empty" style="text-align: center; padding: 50px; background: rgba(255,255,255,0.5); border-radius: 20px;">
                    <p style="font-size: 18px; color: #888;">Belum ada cerita yang tersimpan hari ini 🌷</p>
                </div>
            @endforelse
        </div>

        <div class="rc-sidebar">
            <div class="rc-side-card">
                <h3 style="color: #4A3A35; font-size: 18px; margin-bottom: 15px;">
                    📊 Insight Minggu Ini
                </h3>
                <p style="line-height: 1.6; color: #5c5045;"> 
                    <span style="color: #ff4f7a; font-weight: 800; background: #ffe6ea; padding: 2px 8px; border-radius: 6px;">
                        {{ $insightText }}
                    </span>
                </p>
                <small style="display: block; margin-top: 15px; color: #999; font-style: italic;">
                    "Perasaan kecilmu tetap berarti, terima kasih sudah berani bercerita." ✨
                </small>
            </div>

            <div class="rc-side-card" style="margin-top: 20px; background: linear-gradient(135deg, #ffffff, #fff0f3);">
                <h4 style="font-size: 15px; color: #ff6f91; margin-bottom: 10px;">💡 Tips Hari Ini</h4>
                <p style="font-size: 13px; color: #7a6a63;">
                    {{ $dailyTip ?? 'Jangan lupa untuk selalu menyayangi dirimu sendiri hari ini.' }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="delete-modal" id="deleteModal">
    <div class="delete-card">
        <div class="delete-header">🔔 Konfirmasi</div>
        <h2 style="font-size: 24px; color: #4A3A35; margin-top: 15px;">Hapus cerita ini? 🥺</h2>
        <p style="color: #888; margin: 15px 0 30px;">Tindakan ini tidak dapat dibatalkan, lho.</p>

        <div class="delete-actions">
            <button class="btn-cancel" onclick="closeDeleteModal()">Batal</button>
            <form id="deleteForm" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-delete-confirm">🗑️ Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    
    // 1. Logika Filter Mood (Client-side)
    const filterButtons = document.querySelectorAll(".pill");
    const timelineItems = document.querySelectorAll(".rc-item");

    filterButtons.forEach(button => {
        button.addEventListener("click", function () {
            filterButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            const filterValue = this.dataset.filter;

            timelineItems.forEach(item => {
                if (filterValue === "all") {
                    item.style.display = "flex";
                } else {
                    item.style.display = item.dataset.mood === filterValue ? "flex" : "none";
                }
            });
        });
    });

    // 2. Logika Modal Delete
    const deleteModal = document.getElementById("deleteModal");
    const deleteForm = document.getElementById("deleteForm");
    const openModalButtons = document.querySelectorAll(".open-delete-modal");

    openModalButtons.forEach(button => {
        button.addEventListener("click", function () {
            const id = this.dataset.id;
            // Secara dinamis mengganti action URL form agar sesuai dengan ID curhat
            deleteForm.action = "/ruang-curhat/" + id; 
            deleteModal.classList.add("active");
        });
    });
});

function closeDeleteModal(){
    document.getElementById("deleteModal").classList.remove("active");
}

// Tutup modal jika user klik area luar kartu
window.onclick = function(event) {
    const modal = document.getElementById("deleteModal");
    if (event.target == modal) {
        closeDeleteModal();
    }
}
</script>
@endsection