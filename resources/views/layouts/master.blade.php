@include('layouts.header')
@include('layouts.style-global')
@stack('style-page')
</head>
<body>
  <div class="container-scroller">
    {{-- optional proBanner could be added by pages if needed --}}

    @include('layouts.navbar')

    <div class="container-fluid page-body-wrapper">
      @include('layouts.sidebar')

      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('layouts.footer')
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  @include('layouts.js-global')
  @stack('js-page')
</body>
</html>
