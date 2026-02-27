<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Login &mdash; Sistem Informasi</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('stisla/node_modules/bootstrap/dist/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/assets/fontawesome/all.css') }}">

  <!-- CSS Libraries -->
  <link rel="stylesheet" href="{{ asset('/stisla/node_modules/bootstrap-social/bootstrap-social.css') }}">

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('stisla/assets/css/components.css') }}">

  <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('stisla/assets/sweetalert2/sweetalert2.min.css') }}">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch">
        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white">
          <div class="p-4 m-3">
            {{-- <img src="{{ asset('stisla/assets/img/stisla-fill.svg') }}" alt="logo" width="80" class="shadow-light rounded-circle mb-5 mt-2"> --}}
            <h4 class="text-dark font-weight-normal">Selamat Datang di <span class="font-weight-bold">Si-Kos</span></h4>
            <p class="text-muted">Silakan login untuk mengakses sistem.</p>

            <form method="POST" action="{{ route('login.post') }}" id="form-login">
              @csrf
              <div class="form-group">
                <label for="username">Username</label>
                <div class="input-group">
                  <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                </div>
                <small id="error-username" class="error-text text-danger"></small>
              </div>

              <div class="form-group">
                <div class="d-block">
                  <label for="password" class="control-label">Password</label>
                </div>
                <div class="input-group">
                  <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                </div>
                <small id="error-password" class="error-text text-danger"></small>
              </div>

              <div class="form-group">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                  <label class="custom-control-label" for="remember-me">Ingat Saya</label>
                </div>
              </div>

              <div class="form-group text-right">
                <button type="submit" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                  Login
                </button>
              </div>
            </form>

            <div class="text-center mt-5 text-small">
              Copyright &copy; {{ date('Y') }} Made with 💙
              {{-- <div class="mt-2">
                <a href="#">Privacy Policy</a>
                <div class="bullet"></div>
                <a href="#">Terms of Service</a>
              </div> --}}
            </div>
          </div>
        </div>
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 background-walk-y position-relative overlay-gradient-bottom" 
             data-background="{{ asset('stisla/assets/img/unsplash/rumah1.jpg') }}">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-5 pb-3">
                <h1 class="mb-2 display-4 font-weight-bold">Selamat Datang</h1>
                <h5 class="font-weight-normal text-muted-transparent">di Sistem Informasi Kos</h5>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="{{ asset('stisla/node_modules/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('stisla/node_modules/bootstrap/dist/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('stisla/assets/nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
  <script src="{{ asset('stisla/assets/moment/min/moment.min.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/stisla.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/scripts.js') }}"></script>
  <script src="{{ asset('stisla/assets/js/custom.js') }}"></script>

  <!-- jQuery Validation -->
<script src="{{ asset('stisla/assets/js/validate/jquery.validate.min.js') }}"></script>
  
  <!-- SweetAlert2 -->
<script src="{{ asset('stisla/assets/js/sweetalert2.all.min.js') }}"></script>

  <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $("#form-login").validate({
            rules: {
                username: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                password: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                }
            },
            messages: {
                username: {
                    required: "Username wajib diisi",
                    minlength: "Username minimal 3 karakter",
                    maxlength: "Username maksimal 50 karakter"
                },
                password: {
                    required: "Password wajib diisi",
                    minlength: "Password minimal 3 karakter",
                    maxlength: "Password maksimal 50 karakter"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    beforeSend: function() {
                        // Disable tombol submit
                        $('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
                    },
                    success: function(response) {
                        if (response.status) {
                            // Clear error messages
                            $('.error-text').text('');
                            $('.form-control').removeClass('is-invalid');

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                window.location = response.redirect;
                            });
                        } else {
                            // Clear previous errors
                            $('.error-text').text('');
                            $('.form-control').removeClass('is-invalid');

                            // Show field errors
                            if (response.msgField) {
                                $.each(response.msgField, function(prefix, val) {
                                    $('#error-' + prefix).text(val[0]);
                                    $('#' + prefix).addClass('is-invalid');
                                });
                            }

                            // Show error alert
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });

                            // Enable tombol submit
                            $('button[type="submit"]').prop('disabled', false).html('Login');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan pada server. Silakan coba lagi.'
                        });

                        // Enable tombol submit
                        $('button[type="submit"]').prop('disabled', false).html('Login');
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
  </script>
</body>
</html>