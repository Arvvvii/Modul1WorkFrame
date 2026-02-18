@include('layouts.header')
@include('layouts.style-global')
@include('layouts.auth-background')
</head>
<body>
  <div class="auth-bg">
    <div class="auth-card">
      <div class="auth-logo"><img src="{{ asset('assets/images/logo.svg') }}" alt="logo"></div>

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label for="name" class="form-label">Name</label>
          <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Email</label>
          <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
          <label for="password-confirm" class="form-label">Confirm Password</label>
          <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>

        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-gradient-primary">Register</button>
        </div>
      </form>
    </div>
  </div>

  @include('layouts.js-global')
</body>
</html>
