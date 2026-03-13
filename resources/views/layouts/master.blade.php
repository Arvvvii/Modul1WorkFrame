<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  @include('layouts.header')
  @include('layouts.style-global')
  @stack('style-page')
</head>
<body>
  <div class="container-scroller">
    @include('layouts.navbar')

    <div class="container-fluid page-body-wrapper">
      @include('layouts.sidebar')

      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('layouts.footer')
      </div>
    </div>
  </div>

  @include('layouts.js-global')
  @stack('js-page')
</body>
</html>