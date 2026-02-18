@include('layouts.header')
@include('layouts.style-global')
@include('layouts.auth-background')
</head>
<body>
  <div class="auth-bg">
    <div class="auth-card">
      <div class="auth-logo">
        <img src="{{ asset('assets/images/logo.svg') }}" alt="logo">
      </div>
        <h4 class="auth-title">Halo! mari kita mulai</h4>
        <h6 class="auth-subtitle">Masuk untuk melanjutkan.</h6>

        <form method="POST" action="{{ route('login') }}" class="pt-3">
          @csrf

          <div class="form-group">
            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mt-3">
            <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">MASUK</button>
          </div>

          <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
              <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            <div>
              @if (Route::has('password.request'))
                <a class="text-primary" href="{{ route('password.request') }}">Lupa password?</a>
              @endif
            </div>
          </div>

          <div class="text-center mt-4">
            <span class="text-muted">Belum punya akun? <a href="{{ route('register') }}">Buat</a></span>
          </div>
        </form>
        </form>
      </div>
    </div>
  </div>

  @include('layouts.js-global')
</body>
</html>
