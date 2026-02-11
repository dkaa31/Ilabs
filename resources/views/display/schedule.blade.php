<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Jadwal Pelajaran - {{ $ruangan->nama }}</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="schedule-select">

    <div class="schedule-container">
        <div class="logo">
            <img src="{{ asset('images/pplg.png') }}" alt="logo" />
        </div>
        <h1>Jadwal Pelajaran</h1>
        <h2>{{ $ruangan->nama }}</h2>

        <div class="day-wrapper">
            <div class="row">
                <div class="day-btn"
                    onclick="window.location='{{ route('display.hari', [$ruangan->id_ruangan, 'Senin']) }}'">Senin</div>
                <div class="day-btn"
                    onclick="window.location='{{ route('display.hari', [$ruangan->id_ruangan, 'Selasa']) }}'">Selasa
                </div>
                <div class="day-btn"
                    onclick="window.location='{{ route('display.hari', [$ruangan->id_ruangan, 'Rabu']) }}'">Rabu</div>
            </div>
            <div class="row">
                <div class="day-btn"
                    onclick="window.location='{{ route('display.hari', [$ruangan->id_ruangan, 'Kamis']) }}'">Kamis</div>
                <div class="day-btn"
                    onclick="window.location='{{ route('display.hari', [$ruangan->id_ruangan, 'Jumat']) }}'">Jum'at
                </div>
            </div>
        </div>
    </div>

</body>

</html>
