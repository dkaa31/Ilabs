@extends('siswa.template.master')

@section('title', 'QR Absensi')
@section('page-title', 'QR Absensi Saya')

@section('content')
<div class="d-flex flex-column align-items-center justify-content-center py-4" style="min-height: 70vh;">
  <h5 class="mb-2 text-center">QR Absensi - {{ Auth::user()->nama }}</h5>
  <p class="text-muted mb-3 text-center">QR ini berubah otomatis setiap 30 detik</p>

  <div class="d-flex justify-content-center mb-3 w-100" style="max-width: 300px;">
    <div id="qr-container" style="width: 100%; aspect-ratio: 1/1;"></div>
  </div>

  <p id="countdown" class="text-danger fw-bold text-center"></p>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  new QRCode(document.getElementById("qr-container"), {
    text: "{{ $qrUrl }}",
    width: 240,
    height: 240,
    colorDark: "#000000",
    colorLight: "#ffffff",
    correctLevel: QRCode.CorrectLevel.H
  });

  let timeLeft = 30;
  const countdownEl = document.getElementById('countdown');
  const timer = setInterval(() => {
    countdownEl.textContent = `QR akan diperbarui dalam ${timeLeft} detik`;
    timeLeft--;
    if (timeLeft < 0) {
      clearInterval(timer);
      location.reload();
    }
  }, 1000);
});
</script>
@endsection