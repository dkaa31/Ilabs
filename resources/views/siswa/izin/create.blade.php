@extends('siswa.template.master')

@section('title', 'Ajukan Izin')
@section('page-title', 'Form Pengajuan Izin')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="mb-4">Ajukan Permohonan Izin</h5>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('izin.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="col-12 com-md6 mb-3">
                    <label class="form-label">Jenis Izin</label>
                    <select name="jenis" class="form-select" required onchange="toggleSurat()">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="sakit">Sakit</option>
                        <option value="izin">Izin Keluarga</option>
                        <option value="dispen">Dispensasi</option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-12 com-md6 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-control" required>
                    </div>
                </div>

                <div class="col-12 com-md6 mb-3">
                    <label class="form-label">Tanggal Selesai (Opsional)</label>
                    <input type="date" name="tanggal_selesai" class="form-control">
                </div>

                <div class="col-12 com-md6 mb-3">
                    <label class="form-label">Alasan</label>
                    <textarea name="alasan" class="form-control" rows="3"></textarea>
                </div>

                <div class="col-12 com-md6 mb-3" id="surat-section" style="display:none;">
                    <label class="form-label">Upload Surat Pendukung</label>
                    <input type="file" name="file_surat" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="form-text">Format: PDF/JPG/PNG (max 2MB)</div>
                </div>
                <div class="d-grid d-md-flex gap-2">
                    <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSurat() {
            const jenis = document.querySelector('select[name="jenis"]').value;
            const suratSection = document.getElementById('surat-section');
            suratSection.style.display = (jenis === 'sakit' || jenis === 'dispen') ? 'block' : 'none';
        }
    </script>
@endsection
