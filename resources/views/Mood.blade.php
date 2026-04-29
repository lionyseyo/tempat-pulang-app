@extends('layouts.app')

@section('title', 'Mood Hari Ini')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/Mood.css') }}">
@endpush

@section('content')
<div class="mood-wrapper">
    <div class="mood-header">
        <h1 class="mood-title">Bagaimana perasaanmu hari ini?</h1>
        <p class="mood-subtitle">Beritahu kami bagaimana perasaanmu hari ini!</p>
    </div>

    <div class="profile-settings-card mood-main-card">
        <form method="POST" action="{{ route('mood.store') }}" id="moodForm">
            @csrf
            <input type="hidden" name="mood" id="selectedMood">
            <input type="hidden" name="emoji" id="selectedEmoji">

            <div class="mood-selection-grid">
                <div class="mood-column">
                    <div class="mood-category-group">
                        <div class="category-label">💙 Sedih</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="sedih">😢</span>
                            <span class="emoji-item" data-value="sedih">😭</span>
                            <span class="emoji-item" data-value="sedih">😔</span>
                            <span class="emoji-item" data-value="sedih">😞</span>
                            <span class="emoji-item" data-value="sedih">🥺</span>
                            <span class="emoji-item" data-value="sedih">😟</span>
                        </div>
                    </div>

                    <div class="mood-category-group">
                        <div class="category-label">😡 Marah</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="marah">😠</span>
                            <span class="emoji-item" data-value="marah">😡</span>
                            <span class="emoji-item" data-value="marah">🤬</span>
                            <span class="emoji-item" data-value="marah">😤</span>
                            <span class="emoji-item" data-value="marah">😒</span>
                        </div>
                    </div>

                    <div class="mood-category-group">
                        <div class="category-label">😰 Cemas</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="cemas">😰</span>
                            <span class="emoji-item" data-value="cemas">😥</span>
                            <span class="emoji-item" data-value="cemas">😬</span>
                            <span class="emoji-item" data-value="cemas">😵</span>
                            <span class="emoji-item" data-value="cemas">🤯</span>
                        </div>
                    </div>
                </div>

                <div class="mood-column">
                    <div class="mood-category-group">
                        <div class="category-label">🥱 Lelah</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="lelah">😴</span>
                            <span class="emoji-item" data-value="lelah">😩</span>
                            <span class="emoji-item" data-value="lelah">😫</span>
                            <span class="emoji-item" data-value="lelah">🥱</span>
                            <span class="emoji-item" data-value="lelah">😪</span>
                        </div>
                    </div>

                    <div class="mood-category-group">
                        <div class="category-label">😊 Bahagia</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="bahagia">😊</span>
                            <span class="emoji-item" data-value="bahagia">😁</span>
                            <span class="emoji-item" data-value="bahagia">😄</span>
                            <span class="emoji-item" data-value="bahagia">🥰</span>
                            <span class="emoji-item" data-value="bahagia">😌</span>
                            <span class="emoji-item" data-value="bahagia">🤗</span>
                        </div>
                    </div>

                    <div class="mood-category-group">
                        <div class="category-label">💖 Syukur</div>
                        <div class="emoji-options">
                            <span class="emoji-item" data-value="bersyukur">💖</span>
                            <span class="emoji-item" data-value="bersyukur">💕</span>
                            <span class="emoji-item" data-value="bersyukur">❤️</span>
                            <span class="emoji-item" data-value="bersyukur">🤍</span>
                            <span class="emoji-item" data-value="bersyukur">🙏</span>
                            <span class="emoji-item" data-value="bersyukur">✨</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mood-note-section">
                <label>Ceritakan sedikit tentang hari ini...</label>
                <textarea name="note" class="mood-textarea" placeholder="Tulis ceritamu di sini..."></textarea>
            </div>

            <div class="mood-form-actions">
                <button type="reset" class="btn-danger-light" onclick="window.location.reload();">Batal</button>
                <button type="submit" class="btn-update">Simpan Perasaan</button>
            </div>
        </form>
    </div>

    <div class="message-modal" id="messageModal">
        <div class="message-card">
            <button class="close-x" onclick="closeMessage()">×</button>
            <div class="modal-icon" id="modalEmoji">💌</div>
            <h2>Pesan Untukmu</h2>
            <p id="messageText">Perasaan kecilmu tetap berarti.</p>
            <button class="btn-data-action" onclick="closeMessage()" style="width: 100%;">Tutup</button>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const emojis = document.querySelectorAll(".emoji-item");
    const moodInput = document.getElementById("selectedMood");
    const emojiInput = document.getElementById("selectedEmoji");
    const form = document.getElementById("moodForm");
    const modal = document.getElementById("messageModal");
    const messageText = document.getElementById("messageText");
    const modalEmoji = document.getElementById("modalEmoji");

    emojis.forEach(emoji => {
        emoji.addEventListener("click", function () {
            const moodValue = this.dataset.value;
            const emojiChar = this.innerText;

            // Visual Toggle
            emojis.forEach(e => e.classList.remove("selected"));
            this.classList.add("selected");

            // Update Input
            moodInput.value = moodValue;
            emojiInput.value = emojiChar;
            modalEmoji.innerText = emojiChar;

            // Fetch Motivation (Laravel Route)
            fetch("{{ route('mood.getMotivation') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ mood: moodValue })
            })
            .then(response => response.json())
            .then(data => {
                messageText.innerText = data.message;
                modal.classList.add("active");
            })
            .catch(err => console.error("Error fetching motivation:", err));
        });
    });

    form.addEventListener("submit", function(e){
        if(!moodInput.value){
            e.preventDefault();
            alert("Silakan pilih mood kamu dulu ya 😊");
        }
    });
});

function closeMessage(){
    document.getElementById("messageModal").classList.remove("active");
}
</script>
@endsection