<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SteganoXOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials.navbar')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="stegano-card shadow-sm">
                    <h3 class="text-center mb-4" style="color: var(--primary-color); font-weight: 800;">Login ke Sistem</h3>
                    
                    <p class="text-center text-muted mb-4">Masuk untuk melihat riwayat aktivitas steganografi LSB dan Kriptografi XOR-mu.</p>

                    <x-auth-session-status class="alert alert-success" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Alamat Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="contoh: user@gunadarma.ac.id">
                            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Kata Sandi</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
                            <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-4 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label text-muted">Ingat Saya di perangkat ini</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Masuk Sekarang</button>
                        </div>

                        <div class="text-center mt-4">
                            <span class="text-muted">Belum memiliki akun?</span> 
                            <a href="{{ route('register') }}" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">Daftar di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>