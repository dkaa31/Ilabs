<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slide - {{ $ruangan->nama }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Nonaktifkan cache -->
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
</head>

<body class="index-page">

    <section class="slide">
        <div class="logo">
            <img src="{{ asset('images/pplg.png') }}" alt="Logo" />
        </div>
        <h1>Informasi {{ $ruangan->nama }}</h1>

        <h2>
            @if ($jadwalSekarang)
                @if ($jadwalSekarang->status === 'Istirahat')
                    Istirahat
                @else
                    Jam ke {{ $jadwalSekarang->jam_ke }}
                @endif
            @else
                Tidak ada jadwal
            @endif
        </h2>

        <div class="time" id="clock">–</div>
        <div class="date" id="date">–</div>

        <!-- Durasi Jadwal -->
        <div class="duration">
            @if ($jadwalSekarang)
                {{ \Carbon\Carbon::parse($jadwalSekarang->waktu_mulai)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($jadwalSekarang->waktu_selesai)->format('H:i') }}
            @else
                –
            @endif
        </div>

        <!-- 🔥 KELAS DITAMPILKAN DI BAWAH DURASI -->
        @if ($jadwalSekarang && $jadwalSekarang->status !== 'Istirahat')
            <div class="kelas">{{ $jadwalSekarang->kelas?->nama ?? '–' }}</div>
        @endif

        <!-- Info Guru -->
        <div class="teacher">
            @if ($jadwalSekarang && $jadwalSekarang->status !== 'Istirahat')
                <img src="{{ $jadwalSekarang->guru?->foto ? asset('storage/' . $jadwalSekarang->guru->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($jadwalSekarang->guru?->nama ?? 'Guru') . '&background=0D8ABC&color=fff' }}"
                    alt="{{ $jadwalSekarang->guru?->nama ?? 'Guru' }}" />
                <div class="overlay">
                    <div class="name">{{ $jadwalSekarang->guru?->nama ?? 'Guru' }}</div>
                    <div class="role">{{ $jadwalSekarang->mapel?->nama ?? 'Mapel' }}</div>
                </div>
            @else
                <img src="{{ $ruangan->guru?->foto ? asset('storage/' . $ruangan->guru->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($ruangan->guru?->nama ?? 'Penanggung Jawab') . '&background=0D8ABC&color=fff' }}"
                    alt="{{ $ruangan->guru?->nama ?? 'Penanggung Jawab' }}" />
                <div class="overlay">
                    <div class="name">{{ $ruangan->guru?->nama ?? 'Penanggung Jawab' }}</div>
                    <div class="role">Penanggung Jawab Lab</div>
                </div>
            @endif
        </div>
    </section>

    <script>
        // Auto-refresh tepat di awal menit berikutnya
        function scheduleRefreshAtNextMinute() {
            const now = new Date();
            const seconds = now.getSeconds();
            const milliseconds = now.getMilliseconds();
            const delay = (60 - seconds) * 1000 - milliseconds + 100;
            setTimeout(() => location.reload(), delay);
        }

        // Jam real-time
        function updateClock() {
            const now = new Date();
            document.getElementById("clock").textContent = now.toLocaleTimeString('id-ID', {
                hour12: false
            }) + ' WIB';
            document.getElementById("date").textContent = now.toLocaleDateString('id-ID', {
                weekday: 'long',
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
        }

        updateClock();
        setInterval(updateClock, 1000);
        scheduleRefreshAtNextMinute();
    </script>
</body>

</html>
