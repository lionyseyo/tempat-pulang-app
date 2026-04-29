@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-wrapper">
<div class="settings-header">
    <h1>Hi, {{ Auth::user()->name }} 🤍</h1>
    <p class="user-bio-text">
        {{ Auth::user()->tentangku ? '"' . Auth::user()->tentangku . '"' : 'Senang melihatmu kembali di tempat ternyamanmu.' }}
    </p>
</div>

    <div class="profile-settings-card hero-section-card">
    <div class="mood-container-flexible">
        <h4 class="mood-title">Bagaimana perasaanmu saat ini?</h4>
        
        <div class="mood-list-main">
            <div class="mood-bubble" title="Senang">😊</div>
            <div class="mood-bubble" title="Sedih">😢</div>
            <div class="mood-bubble" title="Marah">😠</div>
            <div class="mood-bubble" title="Khawatir">🥺</div>
            <div class="mood-bubble" title="Tenang">😌</div>
        </div>

        <div class="mood-cta-wrapper">
            <p class="mood-description">Menuangkan perasaan ke dalam tulisan bisa membantu meringankan beban pikiranmu, lho.</p>
            <a href="{{ route('mood.index') }}" class="btn-data-action-modern">
                <i class="fa-regular fa-envelope"></i> Ceritakan Harimu
            </a>
        </div>
    </div>
</div>

    <div class="form-grid">
        <div class="profile-settings-card stats-card">
            <div class="data-item-row" style="grid-template-columns: 60px 1fr;">
                <div class="item-icon-circle icon-export">
                    <i class="fa-solid fa-clock"></i>
                </div>
                <div class="data-info">
                    <h4>Waktu Bersamamu</h4>
                    <p>Total momen yang kamu habiskan untuk merawat diri di sini.</p>
                </div>
            </div>
            <div class="time-display-box">
                <span id="hours">00</span><small>j</small> : 
                <span id="minutes">00</span><small>m</small> : 
                <span id="seconds">00</span><small>d</small>
            </div>
            <p class="small-hint">Kamu luar biasa sudah bertahan sejauh ini 🌸</p>
        </div>

        <div class="profile-settings-card stats-card">
            <div class="data-item-row" style="grid-template-columns: 60px 1fr;">
                <div class="item-icon-circle icon-privacy">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <div class="data-info">
                    <h4>Jejak Langkah</h4>
                    <p>Lihat kembali pola emosimu sepanjang minggu ini.</p>
                </div>
            </div>
            <div class="calendar-preview-box">
                <p>Sudah sejauh mana perjalanan hatimu? 🌷</p>
            </div>
            <a href="{{ route('mood.calendar') }}" class="btn-data-action" style="text-decoration: none; text-align: center; width: 100%;">
                Buka Kalender Mood 🗓️
            </a>
        </div>
    </div>

    <div class="profile-settings-card quote-card" style="margin-top: 30px;">
        <div class="quote-content">
            <i class="fa-solid fa-quote-left"></i>
            <p>"Tidak apa-apa untuk tidak menjadi baik-baik saja. Langit pun butuh waktu untuk mendung sebelum ia kembali cerah."</p>
            <span>— Pelukan Hangat Untukmu</span>
        </div>
    </div>
</div>
@endsection