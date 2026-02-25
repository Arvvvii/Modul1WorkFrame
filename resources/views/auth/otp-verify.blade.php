@include('layouts.header')
@include('layouts.style-global')
@include('layouts.auth-background')
</head>
<body>
  <div class="auth-bg">
    <div class="auth-card">
      <div class="auth-logo">
        <div class="purple-logo" style="font-weight:800;letter-spacing:4px;font-size:34px;text-align:center;margin-bottom:6px;background:linear-gradient(90deg,#8e2de2,#4a00e0);-webkit-background-clip:text;color:transparent">PURPLE</div>
      </div>

      <h4 class="auth-title">Verifikasi Kode OTP</h4>
      <h6 class="auth-subtitle">Masukkan 6 digit kode yang kami kirim ke email Anda</h6>

      <form method="POST" action="{{ route('otp.verify.post') }}" class="pt-3">
        @csrf

        <div class="form-group">
          <input id="otp" name="otp" type="text" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required
                 class="form-control form-control-lg text-center" placeholder="______">
        </div>

        <div class="mt-3">
          <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium">VERIFIKASI</button>
        </div>
      </form>
    </div>
  </div>

  @include('layouts.js-global')
</body>
</html>
