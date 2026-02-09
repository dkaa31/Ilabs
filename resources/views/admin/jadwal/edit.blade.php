@extends('admin.template.master')
@section('title', 'Edit Jadwal')
@section('page-title', 'Edit Jadwal Mengajar')

@section('content')
<div class="bg-white rounded shadow-sm p-4">
  <form action="{{ route('jadwal.update', $jadwal) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row mb-3">
      <div class="col-12">
        <label class="form-label">Hari</label>
        <input type="text" class="form-control" value="{{ $jadwal->hari }}" disabled>
        <input type="hidden" name="hari" value="{{ $jadwal->hari }}">
      </div>
      <div class="col-12">
        <label class="form-label">Jam Ke</label>
        <input type="text" name="jam_ke" class="form-control @error('jam_ke') is-invalid @enderror"
               value="{{ old('jam_ke', $jadwal->jam_ke) }}" placeholder="Contoh: 1 atau Istirahat" required>
        @error('jam_ke')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>
    </div>

    <div class="row mb-3">
  <div class="col-12">
    <label class="form-label">Waktu Mulai</label>
    <input type="time" 
           name="waktu_mulai" 
           class="form-control @error('waktu_mulai') is-invalid @enderror"
           value="{{ old('waktu_mulai', \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i')) }}"
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
           value="{{ old('waktu_selesai', \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i')) }}"
           required>
    @error('waktu_selesai')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
  </div>
</div>

    <div class="mb-3">
      <label class="form-label">Status</label>
      <select name="status" class="form-select" id="status" required>
        <option value="Aktif" {{ (old('status') ?? $jadwal->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Istirahat" {{ (old('status') ?? $jadwal->status) == 'Istirahat' ? 'selected' : '' }}>Istirahat</option>
      </select>
    </div>

    <div id="aktif-fields">
      <div class="row mb-3">
        <div class="col-12">
          <label class="form-label">Guru</label>
          <select name="id_guru" class="form-select @error('id_guru') is-invalid @enderror">
            <option value="">-- Pilih Guru --</option>
            @foreach($gurus as $g)
              <option value="{{ $g->id_guru }}" {{ (old('id_guru') ?? $jadwal->id_guru) == $g->id_guru ? 'selected' : '' }}>
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
              <option value="{{ $m->id_mapel }}" {{ (old('id_mapel') ?? $jadwal->id_mapel) == $m->id_mapel ? 'selected' : '' }}>
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
              <option value="{{ $k->id_kelas }}" {{ (old('id_kelas') ?? $jadwal->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                {{ $k->nama }}
              </option>
            @endforeach
          </select>
          @error('id_kelas')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="col-12">
          <label class="form-label">Ruangan</label>
          <select name="id_ruangan" class="form-select @error('id_ruangan') is-invalid @enderror" required>
            <option value="">-- Pilih Ruangan --</option>
            @foreach($ruangans as $r)
              <option value="{{ $r->id_ruangan }}" {{ (old('id_ruangan') ?? $jadwal->id_ruangan) == $r->id_ruangan ? 'selected' : '' }}>
                {{ $r->nama }}
              </option>
            @endforeach
          </select>
          @error('id_ruangan')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-between">
      <a href="{{ route('jadwal.index') }}" class="btn btn-secondary">Batal</a>
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </form>
</div>

@push('scripts')
<script>
document.getElementById('status').addEventListener('change', function() {
  const fields = document.getElementById('aktif-fields');
  const selects = fields.querySelectorAll('select[name="guru_id"], select[name="mapel_id"], select[name="kelas_id"]');
  
  if (this.value === 'Istirahat') {
    fields.style.display = 'none';
    selects.forEach(sel => {
      sel.removeAttribute('required');
      sel.value = '';
      sel.disabled = true;
    });
  } else {
    fields.style.display = 'block';
    selects.forEach(sel => {
      sel.setAttribute('required', 'required');
      sel.disabled = false;
    });
  }
});
document.getElementById('status').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection