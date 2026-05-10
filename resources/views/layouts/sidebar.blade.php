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

    <li class="nav-item {{ Request::is('/') ? 'active' : '' }}">
      <a class="nav-link" href="{{ url('/') }}">
        <span class="menu-title">Dashboard</span>
        <i class="mdi mdi-home menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#menu-master-dropdown" aria-expanded="false" aria-controls="menu-master-dropdown">
        <span class="menu-title">Menu Master</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-cart menu-icon"></i>
      </a>
      <div class="collapse {{ Request::is('pesan-kantin*') || Request::is('pembayaran*') ? 'show' : '' }}" id="menu-master-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('pesan-kantin*') ? 'active' : '' }}" href="{{ route('kantin.index') }}">Pemesanan</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('pembayaran-customer*') ? 'active' : '' }}" href="{{ route('kantin.pembayaran') }}">Pembayaran</a> 
          </li>
        </ul>
      </div>
    </li>

    @auth
    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#vendor-dropdown" aria-expanded="false" aria-controls="vendor-dropdown">
        <span class="menu-title">Vendor</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-store menu-icon"></i>
      </a>
      <div class="collapse {{ Request::is('menu*') || Request::is('vendor*') || Request::is('transaksi*') || Request::is('admin/scan-kantin*') ? 'show' : '' }}" id="vendor-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('menu*') ? 'active' : '' }}" href="{{ route('menu.index') }}">Master Menu</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('vendor*') ? 'active' : '' }}" href="{{ route('vendor.index') }}">Master Vendor</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('transaksi*') ? 'active' : '' }}" href="{{ route('vendor.transaksi') }}">Data Transaksi</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('admin/scan-kantin*') ? 'active' : '' }}" href="{{ route('admin.scan_kantin') }}">Scan QR Kantin</a> 
          </li>
        </ul>
      </div>
    </li>
    @endauth

    <li class="nav-item {{ Request::is('toko*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('toko.index') }}">
        <span class="menu-title">Data Toko</span>
        <i class="mdi mdi-store menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('kunjungan*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kunjungan.index') }}">
        <span class="menu-title">Kunjungan Toko</span>
        <i class="mdi mdi-map-marker-radius menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('kategori.index') }}">
        <span class="menu-title">Kategori</span>
        <i class="mdi mdi-format-list-bulleted menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('buku.index') }}">
        <span class="menu-title">Buku</span>
        <i class="mdi mdi-book-open-page-variant menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('barang') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.index') }}">
        <span class="menu-title">Barang (CRUD)</span>
        <i class="mdi mdi-tag menu-icon"></i>
      </a>
    </li>

    <li class="nav-item {{ Request::is('barang/scanner') ? 'active' : '' }}">
      <a class="nav-link" href="{{ route('barang.scan') }}">
        <span class="menu-title">Scanner Barang</span>
        <i class="mdi mdi-barcode-scan menu-icon"></i>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#kasir-dropdown" aria-expanded="{{ Request::is('kasir*') ? 'true' : 'false' }}" aria-controls="kasir-dropdown">
        <span class="menu-title">Kasir (POS)</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-cash-register menu-icon"></i>
      </a>
      <div class="collapse {{ Request::is('kasir*') ? 'show' : '' }}" id="kasir-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link {{ Request::is('kasir') ? 'active' : '' }}" href="{{ route('kasir.index') }}">Axios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('kasir-ajax') ? 'active' : '' }}" href="{{ route('kasir.ajax') }}">Ajax</a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#customer-dropdown" aria-expanded="{{ Request::is('customers*') ? 'true' : 'false' }}" aria-controls="customer-dropdown">
        <span class="menu-title">Customer</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-account menu-icon"></i>
      </a>
      <div class="collapse {{ Request::is('customers*') ? 'show' : '' }}" id="customer-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item">
            <a class="nav-link {{ Request::is('customers') ? 'active' : '' }}" href="{{ route('customer.index') }}">Data Customer</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('customers/create/blob') ? 'active' : '' }}" href="{{ route('customer.create.blob') }}">Tambah Customer 1</a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ Request::is('customers/create/file') ? 'active' : '' }}" href="{{ route('customer.create.file') }}">Tambah Customer 2</a>
          </li>
        </ul>
      </div>
    </li>

    <li class="nav-item {{ Request::is('wilayah*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('wilayah.index') }}">
          <span class="menu-title">Wilayah Administrasi</span>
          <i class="mdi mdi-map-marker menu-icon"></i>
        </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" data-bs-toggle="collapse" href="#tugas-modul-dropdown" aria-expanded="false" aria-controls="tugas-modul-dropdown">
        <span class="menu-title">Tugas Modul 4</span>
        <i class="menu-arrow"></i>
        <i class="mdi mdi-jsfiddle menu-icon"></i>
      </a>
      <div class="collapse {{ Request::is('barang-dom') || Request::is('barang-datatables') || Request::is('select-kota') ? 'show' : '' }}" id="tugas-modul-dropdown">
        <ul class="nav flex-column sub-menu">
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('barang-dom') ? 'active' : '' }}" href="/barang-dom">Manipulasi Tabel</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('barang-datatables') ? 'active' : '' }}" href="/barang-datatables">Manipulasi Tabel DataTables</a> 
          </li>
          <li class="nav-item"> 
            <a class="nav-link {{ Request::is('select-kota') ? 'active' : '' }}" href="/select-kota">Select Kota</a> 
          </li>
        </ul>
      </div>
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