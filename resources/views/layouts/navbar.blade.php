

<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <ul class="navbar-nav mr-auto">
        <!-- Tombol Sidebar -->
        <li>
            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav navbar-right">
        <!-- Dropdown Tahun -->
        {{-- <div class="dropdown" id="dropdownTahun">
        <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown">
            <i class="fas fa-calendar-alt"></i> {{ request('tahun', $tahunSekarang) }}
        </button>
        <div class="dropdown-menu">
            @foreach ($tahunRange as $tahun)
            <a class="dropdown-item" href="#" data-value="{{ $tahun }}">{{ $tahun }}</a>
            @endforeach
        </div>
        </div>


        <!-- Select tersembunyi supaya script lama tetap jalan -->
        <select id="tahun" class="d-none">
            @foreach ($tahunRange as $tahun)
                <option value="{{ $tahun }}" {{ request('tahun', $tahunSekarang) == $tahun ? 'selected' : '' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select> --}}


{{--
        <!-- Notifikasi -->
        <li class="dropdown dropdown-list-toggle">
            <a href="#" data-toggle="dropdown" class="nav-link notification-toggle nav-link-lg beep">
                <i class="far fa-bell"></i>
            </a>
            <div class="dropdown-menu dropdown-list dropdown-menu-right">
                <div class="dropdown-header">
                    Notifications
                    <div class="float-right">
                        <a href="#">Mark All As Read</a>
                    </div>
                </div>
                <div class="dropdown-list-content dropdown-list-icons">
                    <a href="#" class="dropdown-item">
                        <div class="dropdown-item-icon bg-info text-white">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="dropdown-item-desc">
                            Welcome to the system!
                            <div class="time">Today</div>
                        </div>
                    </a>
                </div>
                <div class="dropdown-footer text-center">
                    <a href="#">View All <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </li>
        --}}

        <!-- Profil & Logout -->
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" 
                    src="{{ Auth::user()->pegawai && Auth::user()->pegawai->foto 
                            ? asset('storage/' . Auth::user()->pegawai->foto) 
                            : asset('stisla/assets/img/avatar/avatar-1.png') }}" 
                    class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">
                Hi, {{ Auth::user()->pengelola?->nama ?? Auth::user()->penyewa?->nama ?? Auth::user()->username ?? 'User' }}
            </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a href="/profile" class="dropdown-item has-icon">
                    <i class="far fa-user"></i> Profile
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" id="btnLogout" class="dropdown-item has-icon text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Script Logout -->
<script>
    $(document).ready(function() {
        $('#btnLogout').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/logout',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    window.location.href = '/login';
                },
                error: function(xhr) {
                    console.log(xhr);
                    alert('Logout gagal.');
                }
            });
        });
    });
  $('.dropdown-item[data-value]').on('click', function (e) {
    e.preventDefault();
    const tahun = $(this).data('value');
    if (!tahun) return;

    $('#tahun').val(tahun).trigger('change');
    $(this).closest('.dropdown').find('.btn')
      .html('<i class="fas fa-calendar-alt"></i> ' + tahun);

    window.location.href = "{{ url()->current() }}?tahun=" + encodeURIComponent(tahun);
  });

</script>