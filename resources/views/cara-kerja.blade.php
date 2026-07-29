<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cara Kerja - SteganoXOR</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .step-icon {
            font-size: 2.5rem;
            color: var(--primary-color, #198754);
            margin-bottom: 15px;
        }
        .card-step {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 16px;
            border: none;
        }
        .card-step:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
    </style>
</head>
<body class="bg-light">
    @include('partials.navbar')

    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold" style="color: var(--primary-color, #198754);">Bagaimana SteganoXOR Bekerja?</h1>
            <p class="text-muted fs-5">Mengamankan pesan rahasia menggunakan perpaduan Enkripsi XOR dan Steganografi LSB.</p>
        </div>

<!-- Section Penjelasan Algoritma (Fokus LSB) -->
        <div class="row mb-5 justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-sm border-0" style="border-radius: 16px;">
                    <div class="card-body p-4 p-md-5">
                        <h4 class="fw-bold mb-3"><i class="bi bi-eye-slash-fill text-success me-2"></i>Steganografi LSB</h4>
                        <p class="text-secondary" style="line-height: 1.8;">
                            Sistem ini berfokus pada teknik <strong>Steganografi Least Significant Bit (LSB)</strong>, sebuah metode penyembunyian data yang menyisipkan pesan rahasia ke dalam bit paling akhir dari piksel warna sebuah gambar. Perubahan pada bit ke-8 ini sangat mikroskopis, sehingga kualitas visual gambar tidak akan terlihat berubah oleh mata manusia. Sebagai lapisan keamanan ganda, sistem ini juga menyediakan fitur <strong>opsional</strong> berupa <strong>Kriptografi XOR</strong> untuk mengacak teks sebelum disisipkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <hr class="text-muted w-75 mx-auto mb-5">

<!-- Section Proses Encode -->
        <h3 class="fw-bold text-center mb-4">Proses Penyisipan (Encode)</h3>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-shield-lock-fill',
                    'iconColor' => 'text-warning',
                    'title' => '1. Enkripsi Karakter (XOR)',
                    'description' => 'Pesan teks yang diinput akan langsung dienkripsi per karakter menggunakan algoritma XOR jika kata sandi diberikan. Jika tidak ada sandi, teks dibiarkan dalam wujud aslinya.'
                ])
            </div>
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-file-binary-fill',
                    'iconColor' => 'text-primary',
                    'title' => '2. Persiapan Biner & Piksel',
                    'description' => 'Teks pesan diubah menjadi deretan biner 8-bit. Bersamaan dengan itu, sistem membaca piksel gambar dan mengosongkan bit terakhirnya dengan mengubah semua nilai ganjil pada RGB menjadi genap.'
                ])
            </div>
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-images',
                    'iconColor' => 'text-success',
                    'title' => '3. Penyisipan Bit LSB',
                    'description' => 'Sistem memasukkan deretan biner pesan dengan cara menjumlahkannya (+0 atau +1) secara berurutan ke nilai piksel RGB gambar yang sudah disiapkan, lalu menghasilkan gambar stego.'
                ])
            </div>
        </div>

        <!-- Section Proses Decode -->
        <h3 class="fw-bold text-center mb-4">Proses Ekstraksi (Decode)</h3>
        <div class="row g-4 mb-5 justify-content-center">
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-search',
                    'iconColor' => 'text-danger',
                    'title' => '1. Pembacaan Bit LSB',
                    'description' => 'Sistem memindai nilai RGB dari seluruh piksel gambar stego. Jika nilainya ganjil, dicatat sebagai biner "1", dan jika genap sebagai biner "0", membentuk deretan biner panjang.'
                ])
            </div>
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-translate',
                    'iconColor' => 'text-primary',
                    'title' => '2. Konversi Biner ke Teks',
                    'description' => 'Rangkaian biner yang terkumpul dipotong setiap 8-bit dan langsung diterjemahkan kembali menjadi karakter teks (yang mungkin masih dalam keadaan terenkripsi).'
                ])
            </div>
            <div class="col-md-4">
                @include('partials.step-card', [
                    'icon' => 'bi-unlock-fill',
                    'iconColor' => 'text-warning',
                    'title' => '3. Dekripsi Teks (XOR)',
                    'description' => 'Karakter teks yang diekstrak kemudian didekripsi menggunakan operasi XOR dengan kata sandi pengguna untuk menampilkan pesan rahasia yang sesungguhnya.'
                ])
            </div>
        </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>