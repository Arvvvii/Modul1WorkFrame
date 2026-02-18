@include('layouts.header')
@include('layouts.style-global')
@include('layouts.auth-background')
</head>
<body>
  <div class="auth-bg">
    <div class="auth-card">
      <div class="auth-logo"><img src="{{ asset('assets/images/logo.svg') }}" alt="logo"></div>
      <h4 class="auth-title">Reset Password</h4>
      <p class="auth-subtitle">Masukkan email untuk menerima tautan reset.</p>

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        @if (session('status'))
          <div class="alert alert-success" role="alert">{{ session('status') }}</div>
        @endif

        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-gradient-primary">Send Password Reset Link</button>
        </div>
      </form>
    </div>
  </div>

  @include('layouts.js-global')
</body>
</html>
