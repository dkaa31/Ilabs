<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login - Aplikasi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', system-ui, sans-serif;
      overflow-x: hidden;
    }
    .auth-wrapper {
      height: 100vh;
      display: flex;
      flex-direction: row;
    }
    .auth-form-section {
      width: 50%;
      background-color: #ffffff;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1.5rem;
      box-shadow: inset -2px 0 8px rgba(0,0,0,0.05);
    }
    .auth-image-section {
      width: 50%;
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
    }
    .auth-form-container {
      width: 100%;
      max-width: 450px;
    }

    /* Mobile */
    @media (max-width: 991.98px) {
      .auth-wrapper {
        flex-direction: column;
      }
      .auth-form-section,
      .auth-image-section {
        width: 100%;
        height: 50%;
      }
      .auth-image-section {
        display: none;
      }
      .auth-form-container {
        padding: 0 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="auth-wrapper">
    <div class="auth-form-section">
      <div class="auth-form-container">
        <div class="text-center mb-4">
          <h2 class="fw-bold text-dark">Masuk ke Akun Anda</h2>
          <p class="text-muted">Selamat datang! Tolong masukan detail anda</p>
        </div>

        @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $errors->first() }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3 position-relative">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
              <i class="fas fa-user"></i>
            </span>
            <input type="email" name="email" class="form-control ps-5 @error('email') is-invalid @enderror" 
                   placeholder="Email" value="{{ old('email') }}" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3 position-relative">
            <span class="position-absolute top-50 start-0 translate-middle-y ms-3 text-muted">
              <i class="fas fa-lock"></i>
            </span>
            <input type="password" name="password" class="form-control ps-5 @error('password') is-invalid @enderror" 
                   id="passwordInput" placeholder="Kata sandi" required>
            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted" id="togglePassword" style="cursor: pointer;">
              <i class="fas fa-eye"></i>
            </span>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
              <label class="form-check-label small" for="rememberMe">Ingat saya</label>
            </div>
            <a href="{{ route('password.request') }}" class="text-decoration-none small text-primary">Lupa kata sandi?</a>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
        </form>
      </div>
    </div>

    <div class="auth-image-section" style="background-image: url('{{ asset('images/bg.png') }}');"></div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.getElementById('togglePassword').addEventListener('click', function () {
      const input = document.getElementById('passwordInput');
      const icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
      }
    });
  </script>
</body>
</html>