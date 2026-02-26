<nav class="sidebar sidebar-offcanvas" id="sidebar">
  <ul class="nav">
    <li class="nav-item nav-profile">
      <a href="#" class="nav-link">
        <div class="nav-profile-image">
          <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
          <span class="login-status online"></span>
        </div>
        <div class="nav-profile-text d-flex flex-column">
          @auth
            <span class="font-weight-bold mb-2">{{ Auth::user()->name }}</span>
          @else
            <span class="font-weight-bold mb-2">Guest</span>
          @endauth
          <span class="text-secondary text-small">Member</span>
        </div>
        <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('kategori.index') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('buku.index') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ route('barang.index') }}">
        <span class="menu-title">Barang</span>
        <i class="mdi mdi-tag menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#pdf-dropdown" aria-expanded="false" aria-controls="pdf-dropdown">
        <span class="menu-title">Generate PDF</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-file-document menu-icon"></i>
      </a>
      <div class="collapse" id="pdf-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link" href="{{ route('generate.sertifikat') }}" target="_blank" rel="noopener">Sertifikat</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link" href="{{ route('generate.undangan') }}" target="_blank" rel="noopener">Undangan</a> 
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item nav-logout">
      <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-main').submit();">
        <span class="menu-title">Logout</span>
        <i class="mdi mdi-power menu-icon"></i>
      </a>
    </li>
  </ul>
</nav>