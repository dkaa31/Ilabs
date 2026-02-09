<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Jadwal Pelajaran - {{ $hari }}</title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="schedule-page">

  <!-- Logo -->
  <div class="schedule-logo">
    <img src="{{ asset('images/pplg.png') }}" alt="logo" onerror="this.style.display='none'" />
  </div>

  <!-- Nama Ruangan -->
  <h1 class="schedule-header">{{ $ruangan->nama }}</h1>

  <!-- Jadwal -->
  <div class="schedule-container">
    <h2 class="day-title">{{ $hari }}</h2>
    <div class="schedule-grid">
      @forelse ($jadwals as $j)
        <div class="subject-box">
          <div class="subject-name">
            {{ $j->status === 'Istirahat' ? 'Istirahat' : e($j->mapel?->nama ?? '–') }}
          </div>
          <div class="subject-time">
            {{ \Carbon\Carbon::parse($j->waktu_mulai)->format('H:i') }} - 
            {{ \Carbon\Carbon::parse($j->waktu_selesai)->format('H:i') }}
          </div>
          @if($j->status !== 'Istirahat')
            <div class="subject-time">
              {{ $j->guru?->nama ?? '–' }}
            </div>
          @endif
        </div>
      @empty
        <div class="subject-box">
          <div class="subject-name">Tidak ada jadwal</div>
          <div class="subject-time">–</div>
        </div>
      @endforelse
    </div>
  </div>

  <!-- Tombol Kembali -->
  <a href="{{ route('display.jadwal', $ruangan->id_ruangan) }}" class="btn-back">
    ← Pilih Hari Lain
  </a>

</body>
</html>