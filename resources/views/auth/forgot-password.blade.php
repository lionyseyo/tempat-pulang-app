<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - Tempat Pulang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="background: url('{{ asset('images/background.png') }}') no-repeat center center fixed; background-size: cover;">

<div class="login-card">
    <img src="{{ asset('images/bg-login.png') }}" class="top-image" alt="Logo">

    <h1>Atur Ulang Sandi</h1>
    <p class="subtitle">Masukkan email akun kamu dan buat password baru yang mudah diingat.</p>

    @if ($errors->any())
        <div class="error-box">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="input-box">
            <span class="icon"><i class="fa-solid fa-envelope"></i></span>
            <input type="email" name="email" placeholder="Email Terdaftar" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="input-box">
            <span class="icon"><i class="fa-solid fa-key"></i></span>
            <input type="password" name="password" placeholder="Password Baru" required>
        </div>

        <div class="input-box">
            <span class="icon"><i class="fa-solid fa-check-double"></i></span>
            <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" required>
        </div>

        <button type="submit" class="btn-gradient">
            Perbarui Password
        </button>
    </form>

    <div class="signup">
        Sudah ingat passwordnya? <a href="{{ route('login') }}">Kembali Login</a>
    </div>
</div>

</body>
</html>