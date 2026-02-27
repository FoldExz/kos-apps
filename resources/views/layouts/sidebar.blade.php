<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="index.html">SIKOS</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="index.html">St</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="dropdown">
        <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
        <ul class="dropdown-menu">
            <li><a class="nav-link" href="{{ url('/dashboard') }}">Informasi Umum</a></li>
        </ul>
      </li>

      <li class="menu-header">Starter</li>
      <li class="dropdown">
        <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> 
          <span>Data Master</span></a>
          <ul class="dropdown-menu">

              @if (auth()->user()->role == 'pemilik')
                  <li><a class="nav-link" href="{{ url('/user') }}">Tambah User</a></li>
                  <li><a class="nav-link" href="{{ url('/penyewa') }}">Penyewa</a></li>
                  <li><a class="nav-link" href="{{ url('/pengelola') }}">Pengelola</a></li>
                  <li><a class="nav-link" href="{{ url('/tipe_kamar') }}">Tipe Kamar</a></li>
                  <li><a class="nav-link" href="{{ url('/kamar') }}">Kamar</a></li>
              @endif
              @if (auth()->user()->role == 'operator' || auth()->user()->role == 'pemilik')
                  <li><a class="nav-link" href="{{ url('/transaksi_sewa') }}">Transaksi Sewa</a></li>
                  <li><a class="nav-link" href="{{ url('/transaksi_pembayaran') }}">Transaksi Pembayaran</a></li>
              @endif
          </ul>


      </li>

    </ul>
{{-- 
    <div class="mt-4 mb-4 p-3 hide-sidebar-mini">
      <a href="https://getstisla.com/docs" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-rocket"></i> Documentation
      </a>
    </div> --}}
  </aside>
</div>