<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
    @if(request()->routeIs('display.tampilan.select'))
      Pilih Ruangan untuk Tampilan
    @else
      Pilih Ruangan untuk Jadwal
    @endif
  </title>
  <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
</head>
<body class="select-room-page">
  <div class="container-select-room">
    <h1>
      @if(request()->routeIs('display.tampilan.select'))
        Pilih Ruangan untuk Tampilan
      @else
        Pilih Ruangan untuk Jadwal
      @endif
    </h1>
    
    <div class="alert alert-info" style="max-width: 600px; margin: 0 auto 1.5rem;">
      <strong>Pilih ruangan</strong> untuk menampilkan 
      @if(request()->routeIs('display.tampilan.select'))
        tampilan slide
      @else
        jadwal pelajaran
      @endif.
    </div>

    <div class="room-list">
      @forelse ($ruangans as $r)
        <a href="
          @if(request()->routeIs('display.tampilan.select'))
            {{ route('display.tampilan', $r->id_ruangan) }}
          @else
            {{ route('display.jadwal', $r->id_ruangan) }}
          @endif
        " class="room-btn"
        target="_blank" 
        rel="noopener noreferrer">
          {{ $r->nama }}
        </a>
      @empty
        <p>Tidak ada ruangan tersedia.</p>
      @endforelse
    </div>
  </div>
</body>
</html>