@extends('admin.template.master')
@section('title', 'Tambah Jadwal')
@section('page-title', 'Tambah Jadwal Mengajar')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  @if ($errors->any())
    <div class="alert alert-danger mb-3">
      <strong>Gagal menyimpan!</strong>
      <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  @if(session('success'))
    <div class="alert alert-success mb-3">{{ session('success') }}</div>
  @endif

  <form action="{{ route('jadwal.store') }}" method="POST" id="jadwalForm">
    @csrf

    <!-- Hari & Jam Ke -->
    <div class="row mb-3">
      <div class="col-12">
        <label class="form-label">Hari</label>
        <select name="hari" class="form-select @error('hari') is-invalid @enderror" required>
          @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] as $h)
            <option value="{{ $h }}" {{ ($hari ?? old('hari')) == $h ? 'selected' : '' }}>
              {{ $h }}
            </option>
          @endforeach
        </select>
        @error('hari')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label">Jam Ke</label>
        <input type="text" 
               name="jam_ke" 
               class="form-control @error('jam_ke') is-invalid @enderror"
               value="{{ old('jam_ke') }}" 
               placeholder="Contoh: 1 atau Istirahat" 
               required>
        @error('jam_ke')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Waktu Mulai & Selesai -->
    <div class="row mb-3">
      <div class="col-12">
        <label class="form-label">Waktu Mulai</label>
        <input type="time" 
               name="waktu_mulai" 
               class="form-control @error('waktu_mulai') is-invalid @enderror"
               value="{{ old('waktu_mulai') }}" 
               required>
        @error('waktu_mulai')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
      <div class="col-12">
        <label class="form-label">Waktu Selesai</label>
        <input type="time" 
               name="waktu_selesai" 
               class="form-control @error('waktu_selesai') is-invalid @enderror"
               value="{{ old('waktu_selesai') }}" 
               required>
        @error('waktu_selesai')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Status -->
    <div class="row mb-3">
      <div class="col-12">
        <label class="form-label">Status</label>
        <select name="status" class="form-select @error('status') is-invalid @enderror" id="statusSelect" required>
          <option value="Aktif" {{ old('status', 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
          <option value="Istirahat" {{ old('status') == 'Istirahat' ? 'selected' : '' }}>Istirahat</option>
        </select>
        @error('status')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Ruangan (SELALU WAJIB – sesuai struktur DB) -->
    <div class="row mb-3">
      <div class="col-12">
        <label class="form-label">Ruangan</label>
        <select name="id_ruangan" class="form-select @error('id_ruangan') is-invalid @enderror" required>
          <option value="">-- Pilih Ruangan --</option>
          @foreach($ruangans as $r)
            <option value="{{ $r->id_ruangan }}" {{ ($ruanganId ?? old('id_ruangan')) == $r->id_ruangan ? 'selected' : '' }}>
              {{ $r->nama }}
            </option>
          @endforeach
        </select>
        @error('id_ruangan')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <!-- Field hanya untuk status "Aktif" -->
    <div id="aktifFields" style="display:{{ old('status', 'Aktif') == 'Istirahat' ? 'none' : 'block' }};">
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">Guru</label>
          <select name="id_guru" class="form-select @error('id_guru') is-invalid @enderror">
            <option value="">-- Pilih Guru --</option>
            @foreach($gurus as $g)
              <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                {{ $g->nama }}
              </option>
            @endforeach
          </select>
          @error('id_guru')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label">Mata Pelajaran</label>
          <select name="id_mapel" class="form-select @error('id_mapel') is-invalid @enderror">
            <option value="">-- Pilih Mapel --</option>
            @foreach($mapels as $m)
              <option value="{{ $m->id_mapel }}" {{ old('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                {{ $m->nama }}
              </option>
            @endforeach
          </select>
          @error('id_mapel')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">Kelas</label>
          <select name="id_kelas" class="form-select @error('id_kelas') is-invalid @enderror">
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelases as $k)
              <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                {{ $k->nama }}
              </option>
            @endforeach
          </select>
          @error('id_kelas')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <!-- Tombol Aksi -->
    <div class="d-flex justify-content-between">
      <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Simpan</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusSelect = document.getElementById('statusSelect');
    const aktifFields = document.getElementById('aktifFields');
    const guruSelect = document.querySelector('select[name="id_guru"]');
    const mapelSelect = document.querySelector('select[name="id_mapel"]');
    const kelasSelect = document.querySelector('select[name="id_kelas"]');

    function updateFields() {
        if (statusSelect.value === 'Istirahat') {
            aktifFields.style.display = 'none';
            [guruSelect, mapelSelect, kelasSelect].forEach(el => {
                if (el) {
                    el.removeAttribute('required');
                    el.disabled = true;
                    el.value = '';
                }
            });
        } else {
            aktifFields.style.display = 'block';
            [guruSelect, mapelSelect, kelasSelect].forEach(el => {
                if (el) {
                    el.setAttribute('required', 'required');
                    el.disabled = false;
                }
            });
        }
    }

    // Inisialisasi saat halaman dimuat
    updateFields();

    // Update saat status berubah
    statusSelect.addEventListener('change', updateFields);
});
</script>
@endpush
@endsection