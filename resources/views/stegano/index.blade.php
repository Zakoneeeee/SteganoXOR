<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
        <meta name="user-logged-in" content="true">
    @endauth
    
    <title>Aplikasi Steganografi & Kriptografi XOR</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    
<body>
    @include('partials.navbar')

    <div class="container pb-5">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="stegano-card">
                    <div class="text-center mb-4">
                        <h2 style="color: var(--primary-color); font-weight: 700;">Sistem Steganografi LSB</h2>
                        <p class="text-muted">Amankan data teks anda ke dalam media digital tanpa menimbulkan kecurigaan visual</p>
                    </div>

                    <div class="switch-buttons d-flex justify-content-center gap-3 mb-4">
                        <button id="btnEncode" class="btn active w-50" onclick="switchSection('encode')">Encode Pesan</button>
                        <button id="btnDecode" class="btn w-50" onclick="switchSection('decode')">Decode Pesan</button>
                    </div>

                    <div id="sectionEncode" class="section active">
                        <h4 class="mb-3" style="color: var(--primary-color); font-weight: 600;">Menyandi Pesan (Encode)</h4>
                        <p class="alert alert-info">
                            Pilih gambar, masukkan pesan yang ingin disembunyikan, masukkan password jika ingin lebih aman (Opsional), lalu klik <strong>Encode</strong>.
                        </p>

                        <form class="form">
                            @auth
                                <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Catatan Riwayat (Opsional)</label>
                                <input class="form-control notes-input" type="text" placeholder="Contoh: Dokumen rahasia klien A">
                                <small class="text-muted">Catatan ini hanya akan muncul di Dashboard-mu.</small>
                            </div>  
                            @endauth
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Gambar (JPG/PNG)</label>
                                <input class="form-control" type="file" name="baseFile" accept="image/png, image/jpeg" onchange="previewEncodeImage()">
                                <p id="encodeError" class="text-danger mt-1 fw-bold" style="display: none;">Format Salah, File harus jpg/png!</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pesan Rahasia</label>
                                <textarea class="form-control message" rows="4" placeholder="Masukkan pesan yang ingin disembunyikan"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Kata Sandi Rahasia (Opsional)</label>
                                <input class="form-control encrypt-key" type="password" placeholder="Masukkan Kata Sandi Rahasia">
                            </div>
                            <div class="text-end">
                                <button type="button" class="encode btn btn-primary" onclick="encodeMessage()">Mulai Encode</button>
                            </div>
                        </form>

                        <div class="binary mt-4" style="display: none;">
                            <h5>Representasi Biner</h5>
                            <textarea class="form-control message" style="word-wrap: break-word;" readonly></textarea>
                        </div>
                        <div class="images mt-4" style="display: none;">
                            <div class="original mb-3" style="display: none;">
                                <h6>Gambar Original</h6>
                                <canvas class="img-fluid border rounded"></canvas>
                            </div>
                            <div class="nulled mb-3" style="display: none;">
                                <h6>Normalized Image</h6>
                                <canvas class="img-fluid border rounded"></canvas>
                            </div>
                            <div class="message mb-3" style="display: none;">
                                <h6>Message Hidden (Hasil Sukses)</h6>
                                <canvas class="img-fluid border rounded"></canvas>
                                <div class="mt-3 text-center">
                                    <button type="button" class="btn btn-success" onclick="downloadImage()">Download Gambar Hasil</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="sectionDecode" class="section d-none">
                        <h4 class="mb-3" style="color: var(--primary-color); font-weight: 600;">Membaca Pesan (Decode)</h4>
                        <p class="alert alert-info">
                            Pilihlah gambar yang berisi pesan tersembunyi, lalu masukkan kata sandi jika ada, kemudian klik <strong>Decode</strong>.
                        </p>
                        
                        <form class="form">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Pilih Gambar Bersandi</label>
                                <input class="form-control" type="file" name="decodeFile" accept="image/png, image/jpeg" onchange="previewDecodeImage()">
                                <p id="decodeError" class="text-danger mt-1 fw-bold" style="display: none;">Format Salah, File harus berformat jpg/png</p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Masukkan Kata Sandi</label>
                                <input class="form-control decrypt-key" type="password" placeholder="Masukkan Kata Sandi untuk Membaca (Opsional)">
                            </div>
                            <div class="text-end">
                                <button type="button" class="decode btn btn-primary" onclick="decodeMessage()">Mulai Decode</button>
                            </div>
                        </form>

                        <div class="binary-decode mt-4" style="display: none;">
                            <h5>Pesan Tersembunyi yang Ditemukan:</h5>
                            <textarea class="form-control message" style="word-wrap: break-word; font-size: 1.2rem; font-weight: bold; color: var(--primary-color);" readonly></textarea>
                        </div>
                        <div class="decode mt-4" style="display: none;">
                            <h6>Preview Gambar yang Diupload</h6>
                            <canvas class="img-fluid border rounded"></canvas>
                        </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    
    <script>
        // Logika sederhana untuk pindah tab area Encode dan Decode
        function switchSection(mode) {
            if (mode === 'encode') {
                $('#sectionEncode').removeClass('d-none');
                $('#sectionDecode').addClass('d-none');
                $('#btnEncode').addClass('active');
                $('#btnDecode').removeClass('active');
            } else {
                $('#sectionDecode').removeClass('d-none');
                $('#sectionEncode').addClass('d-none');
                $('#btnDecode').addClass('active');
                $('#btnEncode').removeClass('active');
            }
        }
    </script>
</body>
</html>