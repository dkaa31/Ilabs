@extends('guru.template.master')

@section('title', 'Scan Absensi')
@section('page-title', 'Scan QR Absensi')

@section('content')
<div class="container py-3">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8">
      <div class="card p-3 p-md-4 shadow-sm">
        <h5 class="text-center mb-3">Arahkan kamera ke QR siswa</h5>
        <p class="text-center text-muted mb-3 small">
          Pastikan QR jelas, terang, dan tidak terlalu dekat
        </p>

        <div class="position-relative mb-3" style="max-width: 480px; margin: auto;">
          <video id="preview" autoplay playsinline muted
            style="width: 100%; aspect-ratio: 4/3; border-radius: 10px; background: black;">
          </video>
        </div>

        <div id="result" class="text-center"></div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>

<script>
const video = document.getElementById("preview");
const canvas = document.createElement("canvas");
const ctx = canvas.getContext("2d");

let scanning = true;

// AKSES KAMERA
navigator.mediaDevices.getUserMedia({
    video: {
        facingMode: "environment",
        width: { ideal: 640 },
        height: { ideal: 480 }
    }
})
.then(stream => {
    video.srcObject = stream;
    video.setAttribute("playsinline", true);
    video.play();
    requestAnimationFrame(scanQR);
})
.catch(err => {
    document.getElementById('result').innerHTML =
        `<div class="alert alert-danger">Kamera tidak bisa diakses</div>`;
    console.error(err);
});

// FUNGSI SCAN
function scanQR() {
    if (!scanning) return;

    if (video.readyState === video.HAVE_ENOUGH_DATA) {

        // PERKECIL canvas (BIAR CEPAT & STABIL)
        canvas.width = 480;
        canvas.height = 360;

        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

        const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "attemptBoth"
        });

        if (code) {
            scanning = false;
            handleQR(code.data);
            return;
        }
    }

    requestAnimationFrame(scanQR);
}

function handleQR(data) {
    const resultDiv = document.getElementById("result");

    if (!data.includes('/absensi/verify/')) {
        resultDiv.innerHTML = `
            <div class="alert alert-warning">
                QR tidak valid
            </div>
        `;
        resetScan();
        return;
    }

    const token = data.split('/').pop();
    verifyToken(token);
}

function resetScan() {
    setTimeout(() => {
        scanning = true;
        requestAnimationFrame(scanQR);
    }, 2000);
}

// VERIFIKASI TOKEN
function verifyToken(token) {
    fetch("{{ route('absensi.verify') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ token })
    })
    .then(res => res.json())
    .then(data => {
        const resultDiv = document.getElementById("result");

        if (data.success) {
            resultDiv.innerHTML = `
                <div class="alert alert-success">
                    <h5>${data.message}</h5>
                    <p>${data.user} (${data.role})</p>
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="alert alert-danger">${data.message}</div>
            `;
            resetScan();
        }
    })
    .catch(err => {
        console.error(err);
        document.getElementById("result").innerHTML =
            `<div class="alert alert-danger">Error verifikasi</div>`;
        resetScan();
    });
}
</script>
@endsection
