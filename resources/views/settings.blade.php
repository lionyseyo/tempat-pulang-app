@extends('layouts.app')

@section('title', 'Settings - Tempat Pulang')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h1>Settings</h1>
        <p>Kelola informasi akun dan preferensi datamu.</p>
    </div>

    <div class="settings-tabs">
        <button class="tab-btn active" onclick="openTab(event, 'account-settings')">Account Settings</button>
        <button class="tab-btn" onclick="openTab(event, 'account-data')">Account Data</button>
    </div>

    <div id="account-settings" class="tab-content active" style="display: block;">
        <div class="profile-settings-card">
            @if(session('success'))
                <div class="alert-success" style="background: #ecfdf5; color: #065f46; padding: 15px; border-radius: 15px; margin-bottom: 20px; border: 1px solid #a7f3d0;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }} ✨
                </div>
            @endif

            <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="profile-layout-grid">
                    <div class="avatar-column">
                        <label class="field-label-text">Your Profile Picture</label>
                        <div class="avatar-wrapper">
                            <img id="avatar-preview" 
                                 src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&background=F1BCAE&color=fff' }}" 
                                 alt="Avatar">
                        </div>
                        <div class="avatar-btns">
                            <label for="avatar-input" class="btn-upload-label">Upload New</label>
                            <input type="file" id="avatar-input" name="avatar" hidden accept="image/*" onchange="previewImage(this);">
                            
                            <button type="button" class="btn-remove-lite" onclick="confirmAvatarReset()">
                                Reset
                            </button>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-grid-inner">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <div class="input-wrapper-badge">
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" readonly class="readonly-input">
                                    <span class="badge-verified-inline"><i class="fa-solid fa-circle-check"></i> Verified</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 20px;">
                            <label>Bio / Tentang Aku</label>
                            <textarea name="tentangku" rows="4" placeholder="Tuliskan sedikit tentang dirimu...">{{ old('tentangku', auth()->user()->tentangku) }}</textarea>
                        </div>

                        <div class="form-actions-align">
                            <button type="submit" class="btn-save-settings">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>

            <form id="form-reset-avatar" action="{{ route('profil.resetAvatar') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div id="account-data" class="tab-content" style="display: none;">
        <div class="settings-data-layout">
            <div class="profile-settings-card data-card-general">
                <h3 class="section-card-title">Data Management</h3>
                <div class="data-item-row">
                    <div class="item-icon-circle icon-export">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                    </div>
                    <div class="data-info-text">
                        <h4>Export My Data</h4>
                        <p>Unduh semua kenangan dan curhatan kamu dalam format PDF.</p>
                    </div>
                    <div class="item-action-btn">
                        <a href="{{ route('settings.export') }}" class="btn-data-secondary">Download PDF</a>
                    </div>
                </div>
            </div>

            <div class="profile-settings-card data-card-danger" style="margin-top: 25px;">
                <div class="danger-header-row">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <h3>Danger Zone</h3>
                </div>
                <p class="danger-description">Tindakan di bawah ini permanen dan tidak bisa dibatalkan.</p>
                
                <div class="data-item-row danger-item">
                    <div class="data-info-text">
                        <h4>Delete All History</h4>
                        <p>Hapus semua riwayat mood dan curhat tanpa menghapus akun.</p>
                    </div>
                    <form action="{{ route('settings.clearHistory') }}" method="POST" onsubmit="return confirm('Hapus semua riwayat?')">
                        @csrf
                        <button type="submit" class="btn-danger-outline">Clear History</button>
                    </form>
                </div>

                <div class="data-item-row danger-item">
                    <div class="data-info-text">
                        <h4>Delete Account</h4>
                        <p>Hapus akun dan seluruh data secara permanen.</p>
                    </div>
                    <form action="{{ route('settings.deleteAccount') }}" method="POST" onsubmit="return confirm('Hapus akun permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger-solid">Delete Account</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview gambar saat dipilih dari komputer
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Fungsi Konfirmasi Reset Avatar (Memicu Form POST Tersembunyi)
    function confirmAvatarReset() {
        if (confirm('Yakin ingin menghapus foto profil dan kembali ke default?')) {
            document.getElementById('form-reset-avatar').submit();
        }
    }

    // Navigasi Tab (Account Settings vs Account Data)
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.classList.add("active");
    }
</script>
@endsection