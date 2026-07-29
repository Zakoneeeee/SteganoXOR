<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - SteganoXOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials.navbar')

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="stegano-card shadow-sm">
                    <h3 class="text-center mb-4" style="color: var(--primary-color); font-weight: 800;">Buat Akun Baru</h3>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Miskah Nurzakwan">
                            <x-input-error :messages="$errors->get('name')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Alamat Email</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Masukkan email aktif">
                            <x-input-error :messages="$errors->get('email')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Kata Sandi</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                            <x-input-error :messages="$errors->get('password')" class="text-danger mt-1 small" />
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-bold">Konfirmasi Kata Sandi</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                            <x-input-error :messages="$errors->get('password_confirmation')" class="text-danger mt-1 small" />
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary fw-bold py-2">Daftar Akun</button>
                        </div>

                        <div class="text-center mt-4">
                            <span class="text-muted">Sudah punya akun?</span> 
                            <a href="{{ route('login') }}" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">Masuk di sini</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>