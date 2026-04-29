<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Tempat Pulang</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body style="background: url('{{ asset('images/background.png') }}') no-repeat center center fixed; background-size: cover;">

<div class="login-card">
    <img src="{{ asset('images/bg-login.png') }}" class="top-image" alt="Logo">

    <h1>Selamat Datang</h1>
    <p class="subtitle">Mari rawat suasana hatimu hari ini!</p>

    @if ($errors->any())
        <div class="error-box">
            <i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-box">
            <span class="icon"><i class="fa-solid fa-envelope"></i></span>
            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="input-box">
            <span class="icon"><i class="fa-solid fa-lock"></i></span>
            <input type="password" name="password" placeholder="Password" required>
        </div>

<div class="option-row">
    <label class="remember-me">
        <input type="checkbox" name="remember">
        <span>Remember me</span>
    </label>
    <a href="{{ route('password.request') }}" class="forgot">Forgot password?</a>
</div>

        <button type="submit" class="btn-gradient">
            Masuk
        </button>
    </form>

    <div class="divider">
        <span>Atau masuk dengan</span>
    </div>

    <div class="social-login">
        <a href="{{ route('google.login') }}" class="social-btn">
            <img src="https://cdn.jsdelivr.net/gh/devicons/devicon/icons/google/google-original.svg" class="social-icon">
            <span>Google</span>
        </a>
    </div>

    <div class="signup">
        Belum punya akun? <a href="{{ route('register') }}">Sign up</a>
    </div>
</div>

</body>
</html>