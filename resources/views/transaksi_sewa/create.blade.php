{{-- <form action="{{ url('/transaksi_sewa/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Penyewa</label>
                    <select name="id_penyewa" id="id_penyewa" class="form-control" required>
                        <option value="">-- Pilih Penyewa --</option>
                        @foreach($penyewa as $p)
                            <option value="{{ $p->id_penyewa }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_penyewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kamar</label>
                    <select name="id_kamar" id="id_kamar" class="form-control" required>
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamar as $k)
                            <option value="{{ $k->id_kamar }}">{{ $k->no_kamar }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kamar" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Sewa</label>
                    <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" required>
                    <small id="error-tanggal_sewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lama Sewa (bulan)</label>
                    <select name="lama_sewa" id="lama_sewa" class="form-control" required>
                        <option value="">-- Pilih Lama Sewa --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    <small id="error-lama_sewa" class="error-text form-text text-danger"></small>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-danger btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                id_penyewa: { required: true },
                id_kamar: { required: true },
                tanggal_sewa: { required: true, date: true },
                lama_sewa: { required: true },
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataTransaksi.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script> --}}


{{-- 
<form action="{{ url('/transaksi_sewa/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Transaksi Sewa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Penyewa <span class="text-danger">*</span></label>
                    <select name="id_penyewa" id="id_penyewa" class="form-control" required>
                        <option value="">-- Pilih Penyewa --</option>
                        @foreach($penyewa as $p)
                            <option value="{{ $p->id_penyewa }}">{{ $p->nama }} - {{ $p->no_hp }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_penyewa" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kamar</label>
                    <select name="id_kamar" id="id_kamar" class="form-control" required>
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamar as $k)
                            <option value="{{ $k->id_kamar }}">{{ $k->no_kamar }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_kamar" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Sewa <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" required>
                    <small id="error-tanggal_sewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lama Sewa (bulan) <span class="text-danger">*</span></label>
                    <select name="lama_sewa" id="lama_sewa" class="form-control" required>
                        <option value="">-- Pilih Lama Sewa --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    <small id="error-lama_sewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="alert alert-info" id="info-batas-sewa" style="display: none;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Tanggal Batas Sewa:</strong> <span id="preview-batas-sewa">-</span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Set tanggal sewa default ke hari ini (dipindahkan ke luar ready agar jalan setiap modal dibuka)
    var today = new Date().toISOString().split('T')[0];
    $('#tanggal_sewa').val(today);

    $(document).ready(function() {
        // Preview tanggal batas sewa otomatis
        function hitungBatasSewa() {
            var tanggalSewa = $('#tanggal_sewa').val();
            var lamaSewa = $('#lama_sewa').val();

            if (tanggalSewa && lamaSewa) {
                var date = new Date(tanggalSewa);
                date.setMonth(date.getMonth() + parseInt(lamaSewa));
                
                var batasSewa = date.toLocaleDateString('id-ID', { 
                    day: '2-digit', 
                    month: 'long', 
                    year: 'numeric' 
                });

                $('#preview-batas-sewa').text(batasSewa);
                $('#info-batas-sewa').slideDown();
            } else {
                $('#info-batas-sewa').slideUp();
            }
        }

        // Update preview saat tanggal atau lama sewa berubah
        $('#tanggal_sewa, #lama_sewa').on('change', function() {
            hitungBatasSewa();
        });

        $("#form-tambah").validate({
            rules: {
                id_penyewa: { required: true },
                id_kamar: { required: true },
                tanggal_sewa: { required: true, date: true },
                lama_sewa: { required: true, min: 1 },
            },
            messages: {
                id_penyewa: { required: "Pilih penyewa terlebih dahulu" },
                id_kamar: { required: "Pilih kamar terlebih dahulu" },
                tanggal_sewa: { required: "Tanggal sewa harus diisi" },
                lama_sewa: { required: "Pilih lama sewa" },
            },
            submitHandler: function(form) {
                // Disable button untuk prevent double submit
                var submitBtn = $(form).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            dataTransaksi.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server'
                        });
                    },
                    complete: function() {
                        // Re-enable button
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script> --}}


<form action="{{ url('/transaksi_sewa/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Transaksi Sewa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>Penyewa <span class="text-danger">*</span></label>
                    <select name="id_penyewa" id="id_penyewa" class="form-control" required>
                        <option value="">-- Pilih Penyewa --</option>
                        @foreach($penyewa as $p)
                            <option value="{{ $p->id_penyewa }}">{{ $p->nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-id_penyewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kamar <span class="text-danger">*</span></label>
                    <select name="id_kamar" id="id_kamar" class="form-control" required>
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamar as $k)
                            <option value="{{ $k->id_kamar }}">{{ $k->no_kamar }} </option>
                        @endforeach
                    </select>
                    <small id="error-id_kamar" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Tanggal Sewa <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" required>
                    <small id="error-tanggal_sewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Lama Sewa (bulan) <span class="text-danger">*</span></label>
                    <select name="lama_sewa" id="lama_sewa" class="form-control" required>
                        <option value="">-- Pilih Lama Sewa --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $i }} Bulan</option>
                        @endfor
                    </select>
                    <small id="error-lama_sewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="alert alert-info" id="info-batas-sewa" style="display: none;">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Tanggal Batas Sewa:</strong> <span id="preview-batas-sewa">-</span>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        // Set tanggal sewa default ke hari ini
        var today = new Date().toISOString().split('T')[0];
        $('#tanggal_sewa').val(today);

        // Preview tanggal batas sewa otomatis
        function hitungBatasSewa() {
            var tanggalSewa = $('#tanggal_sewa').val();
            var lamaSewa = $('#lama_sewa').val();

            if (tanggalSewa && lamaSewa) {
                var date = new Date(tanggalSewa);
                date.setMonth(date.getMonth() + parseInt(lamaSewa));
                
                var batasSewa = date.toLocaleDateString('id-ID', { 
                    day: '2-digit', 
                    month: 'long', 
                    year: 'numeric' 
                });

                $('#preview-batas-sewa').text(batasSewa);
                $('#info-batas-sewa').slideDown();
            } else {
                $('#info-batas-sewa').slideUp();
            }
        }

        // Update preview saat tanggal atau lama sewa berubah
        $('#tanggal_sewa, #lama_sewa').on('change', function() {
            hitungBatasSewa();
        });

        $("#form-tambah").validate({
            rules: {
                id_penyewa: { required: true },
                id_kamar: { required: true },
                tanggal_sewa: { required: true, date: true },
                lama_sewa: { required: true, min: 1 },
            },
            // messages: {
            //     id_penyewa: { required: "Pilih penyewa terlebih dahulu" },
            //     id_kamar: { required: "Pilih kamar terlebih dahulu" },
            //     tanggal_sewa: { required: "Tanggal sewa harus diisi" },
            //     lama_sewa: { required: "Pilih lama sewa" },
            // },
            submitHandler: function(form) {
                // Disable button untuk prevent double submit
                var submitBtn = $(form).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: true
                            });
                            dataTransaksi.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan server'
                        });
                    },
                    // complete: function() {
                    //     // Re-enable button
                    //     submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                    // }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>