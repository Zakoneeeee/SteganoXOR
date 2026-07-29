<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Riwayat - SteganoXOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('partials.navbar')

    <div class="container pb-5">
        <h2 class="mb-4" style="color: var(--primary-color); font-weight: 800;">Dashboard Riwayat Aktivitas</h2>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-0 border-start border-success border-4 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.85rem;">Total Encode</p>
                        <h3 class="fw-bold text-success mb-0">{{ $histories->where('action_type', 'encode')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card shadow-sm border-0 border-start border-warning border-4 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.85rem;">Total Decode</p>
                        <h3 class="fw-bold text-warning mb-0">{{ $histories->where('action_type', 'decode')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3 mt-md-0">
                <div class="card shadow-sm border-0 border-start border-primary border-4 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase fw-bold mb-1" style="font-size: 0.85rem;">Karakter Tersembunyi</p>
                        <h3 class="fw-bold text-primary mb-0">{{ $histories->where('action_type', 'encode')->sum('message_length') }} <small class="fs-6 text-muted">Huruf</small></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold" style="color: var(--text-color);">Log Aktivitas Steganografi</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="px-4 py-3">Tanggal Aktivitas</th>
                                <th class="px-4 py-3">Jenis Aksi</th>
                                <th class="px-4 py-3">Nama Berkas Gambar</th>
                                <th class="px-4 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($histories as $history)
                                <tr>
                                    <td class="px-4 py-3 text-muted">{{ $history->created_at->format('d M Y, H:i') }} Wib</td>
                                    <td class="px-4 py-3">
                                        @if($history->action_type == 'encode')
                                            <span class="badge bg-success px-2 py-1">ENCODE</span>
                                        @else
                                            <span class="badge bg-warning text-dark px-2 py-1">DECODE</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 fw-medium text-dark">{{ $history->file_name ?? 'image.png' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary btn-detail" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#detailModal"
                                                    data-date="{{ $history->created_at->format('d M Y, H:i') }}"
                                                    data-type="{{ strtoupper($history->action_type) }}"
                                                    data-file="{{ $history->file_name }}"
                                                    data-path="{{ $history->file_path  }}"
                                                    data-length="{{ $history->message_length }}"
                                                    data-key="{{ $history->xor_key ?? '(Tanpa Password)' }}"
                                                    data-notes="{{ $history->notes ?? '(Tidak ada catatan)' }}">
                                                <i class="bi bi-eye-fill"></i> Detail
                                            </button>

                                            <form action="{{ route('history.destroy', $history->id) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus log riwayat ini secara permanen?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="bi bi-trash3-fill"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-5 text-center text-muted fw-medium">
                                        Belum ada riwayat aktivitas yang terekam di akun ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow" style="border-radius: 16px;">
                <div class="modal-header text-white" style="background-color: var(--primary-color); border-top-left-radius: 16px; border-top-right-radius: 16px;">
                    <h5 class="modal-title fw-bold"><i class="bi bi-info-circle-fill me-2"></i>Detail Informasi Aktivitas</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <table class="table table-borderless align-middle mb-0">
                        <!-- Area Preview & Download Gambar -->
                        <div id="modal-image-area" class="text-center mb-4 d-none">
                            <p class="text-muted fw-bold mb-2 small"><i class="bi bi-image me-1"></i>Arsip Gambar Steganografi</p>
                            <img id="modal-image-preview" src="" alt="Encoded Image" class="img-fluid rounded border shadow-sm mb-3" style="max-height: 200px;">
                            <br>
                            <a id="modal-download-btn" href="#" download="stegano_arsip.png" class="btn btn-sm btn-success fw-bold">
                                <i class="bi bi-cloud-arrow-down-fill me-1"></i> Download Gambar
                            </a>
                        </div>
                        <tr>
                            <td class="fw-bold text-muted w-40">Waktu Eksekusi</td>
                            <td>: <span id="modal-date" class="text-dark fw-medium"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Jenis Operasi</td>
                            <td>: <span id="modal-type" class="badge"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Nama File Gambar</td>
                            <td>: <span id="modal-file" class="text-dark fw-medium"></span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Panjang Teks Rahasia</td>
                            <td>: <span id="modal-length" class="text-dark"></span> Karakter</td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted">Password Kunci XOR</td>
                            <td>: <code id="modal-key" class="fs-6 text-danger fw-bold"></code></td>
                        </tr>
                        <tr>
                            <td class="fw-bold text-muted text-wrap" colspan="2">
                                <div class="mt-3 p-3 bg-light rounded-3">
                                    <div class="fw-bold text-dark mb-1"><i class="bi bi-pencil-square me-1"></i>Catatan Pengguna:</div>
                                    <span id="modal-notes" class="text-secondary italic"></span>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-secondary fw-bold px-4" data-bs-close="modal" data-bs-dismiss="modal" style="border-radius: 8px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.btn-detail').on('click', function() {
                // Ambil data-atribut dari tombol yang diklik
                const date = $(this).data('date');
                const type = $(this).data('type');
                const file = $(this).data('file');
                const path = $(this).data('path');
                const length = $(this).data('length');
                const key = $(this).data('key');
                const notes = $(this).data('notes');

                // Tembakkan nilai variabel ke elemen dalam modal pop-up
                $('#modal-date').text(date);
                $('#modal-file').text(file);
                $('#modal-length').text(length);
                $('#modal-key').text(key);
                $('#modal-notes').text(notes);

                // Tampilkan gambar jika ada path-nya (Jika itu encode)
                if(path) {
                    $('#modal-image-area').removeClass('d-none');
                    $('#modal-image-preview').attr('src', path);
                    $('#modal-download-btn').attr('href', path);
                } else {
                    $('#modal-image-area').addClass('d-none');
                }
                // Set warna badge tipe operasi secara dinamis
                const $typeBadge = $('#modal-type');
                $typeBadge.text(type);
                if(type === 'ENCODE') {
                    $typeBadge.removeClass('bg-warning text-dark').addClass('bg-success text-white');
                } else {
                    $typeBadge.removeClass('bg-success text-white').addClass('bg-warning text-dark');
                }
            });
        });
    </script>
</body>
</html>