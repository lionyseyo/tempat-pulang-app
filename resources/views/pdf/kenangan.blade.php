<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buku Kenangan - Tempat Pulang</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #4A3A35;
            padding: 30px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #F1BCAE;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .user-info {
            margin-bottom: 20px;
            font-size: 14px;
        }
        .curhat-card {
            background-color: #FDF0ED;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 5px solid #E2A695;
        }
        .date {
            font-size: 12px;
            color: #E2A695;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .text {
            font-size: 14px;
            line-height: 1.6;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #aaa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Buku Kenangan</h1>
        <p>Tempat Pulang - Setiap cerita punya tempat untuk berlabuh.</p>
    </div>

    <div class="user-info">
        <p><strong>Pemilik Kenangan:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Dicetak pada:</strong> {{ date('d F Y, H:i') }}</p>
    </div>

    @forelse($dataCurhat as $item)
        <div class="curhat-card">
            <div class="date">{{ $item->created_at->format('d M Y, H:i') }}</div>
            <div class="text">
                {{ $item->isi_curhat }} {{-- Pastikan nama kolom di DB adalah 'isi_curhat' --}}
            </div>
        </div>
    @empty
        <p style="text-align: center; color: #999;">Belum ada cerita yang tersimpan.</p>
    @endforelse

    <footer>
        &copy; {{ date('Y') }} Aplikasi Tempat Pulang - Digital Journaling
    </footer>
</body>
</html>