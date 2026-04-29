@extends('layouts.app')

@section('title','Kotak Kenangan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kotak_kenangan.css') }}">
@endpush

@section('content')
<div class="kenangan-container">

    <div class="kenangan-header">
        <div class="kenangan-text">
            <h1>Kotak Kenangan 📸✨</h1>
            <p>Abadikan momen kecil yang membuatmu bersyukur hari ini.</p>

            <form action="{{ route('kenangan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="upload-buttons">
                    <label class="upload-btn">
                        <i class="fa-solid fa-image" style="color: #6bcf9b;"></i> Upload Foto
                        <input type="file" name="foto" id="fotoInput" accept="image/*" hidden>
                        <span id="fotoName" class="file-name"></span>
                    </label>
                    <label class="upload-btn">
                        <i class="fa-solid fa-video" style="color: #7ab6ff;"></i> Upload Video
                        <input type="file" name="video" id="videoInput" accept="video/*" hidden>
                        <span id="videoName" class="file-name"></span>
                    </label>
                </div>
                <textarea name="caption" class="caption-input" placeholder="Tuliskan cerita singkat tentang momen ini..."></textarea>
                <button type="submit" class="save-btn">✨ Simpan Kenangan</button>
            </form>
        </div>
    </div>

    <div class="kenangan-list">
        <div class="kenangan-title" style="margin-bottom: 35px;">
            <h2 style="color: #4A3A35; font-size: 26px;">Galeri Kenanganmu 🧸🌷</h2>
        </div>

        <div class="kenangan-grid">
            @forelse($kenangans as $kenangan)
                <div class="kenangan-card">
                    <div class="card-header-action">
                        <div class="tanggal">{{ $kenangan->created_at->format('d M Y') }}</div>
                        
                        <form action="{{ route('kenangan.destroy', $kenangan->id) }}" method="POST" onsubmit="return confirm('Hapus kenangan indah ini? 🥺')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-btn">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>

                    @if($kenangan->foto)
                        <img src="{{ asset('storage/'.$kenangan->foto) }}" alt="Foto Kenangan">
                    @endif

                    @if($kenangan->video)
                        <video controls>
                            <source src="{{ asset('storage/'.$kenangan->video) }}">
                        </video>
                    @endif

                    @if($kenangan->caption)
                        <div class="judul">{{ $kenangan->caption }}</div>
                    @endif
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; padding: 100px 0;">
                    <p style="color: #aaa;">Kotak kenanganmu masih kosong. 🌸</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection