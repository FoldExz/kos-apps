

@empty($transaksi)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title text-danger">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Data Tidak Ditemukan</h5>
                Data transaksi sewa tidak tersedia.
            </div>
        </div>
    </div>
</div>
@else

<form action="{{ url('/transaksi_sewa/' . $transaksi->id_transaksi_sewa . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-edit"></i> Edit Transaksi Sewa
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <!-- Info Status Saat Ini -->
                <div class="alert alert-{{ $transaksi->status == 'aktif' ? 'success' : 'warning' }}">
                    <i class="icon fas fa-info-circle"></i>
                    <strong>Status Saat Ini: </strong>
                    <span class="badge badge-{{ $transaksi->status == 'aktif' ? 'success' : 'danger' }}">
                        {{ strtoupper($transaksi->status) }}
                    </span>
                    <br>
                    <small>
                        @if($transaksi->status == 'tidak_aktif')
                            Transaksi ini sudah expired. Perpanjang dengan menambah lama sewa untuk mengaktifkan kembali.
                        @else
                            Batas sewa: <strong>{{ date('d-m-Y', strtotime($transaksi->tanggal_batas_sewa)) }}</strong>
                        @endif
                    </small>
                </div>

                <div class="form-group">
                    <label>Penyewa</label>
                    <select name="id_penyewa" id="id_penyewa" class="form-control" required>
                        <option value="">-- Pilih Penyewa --</option>
                        @foreach($penyewa as $p)
                            <option value="{{ $p->id_penyewa }}" 
                                {{ $transaksi->id_penyewa == $p->id_penyewa ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_penyewa" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kamar</label>
                    <select name="id_kamar" id="id_kamar" class="form-control" required>
                        <option value="">-- Pilih Kamar --</option>
                        @foreach($kamar as $k)
                            <option value="{{ $k->id_kamar }}" 
                                {{ $transaksi->id_kamar == $k->id_kamar ? 'selected' : '' }}
                                {{ $k->status == 'tidak_tersedia' && $transaksi->id_kamar != $k->id_kamar ? 'disabled' : '' }}>
                                {{ $k->no_kamar }} 
                                @if($k->status == 'tidak_tersedia' && $transaksi->id_kamar != $k->id_kamar)
                                    (Tidak Tersedia)
                                @endif
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_kamar" class="error-text form-text text-danger"></small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Sewa <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_sewa" id="tanggal_sewa" class="form-control" 
                                   value="{{ date('Y-m-d', strtotime($transaksi->tanggal_sewa)) }}" required>
                            <small id="error-tanggal_sewa" class="error-text form-text text-danger"></small>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lama Sewa (Bulan) <span class="text-danger">*</span></label>
                            <input type="number" name="lama_sewa" id="lama_sewa" class="form-control" 
                                   value="{{ $transaksi->lama_sewa }}" min="1" required>
                            <small id="error-lama_sewa" class="error-text form-text text-danger"></small>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <strong><i class="fas fa-calendar-check"></i> Preview Batas Sewa Baru:</strong><br>
                    <span id="tanggal_batas_sewa_preview" class="h5 text-primary">
                        {{ date('d-m-Y', strtotime($transaksi->tanggal_batas_sewa)) }}
                    </span>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Status akan otomatis <strong>AKTIF</strong> jika batas sewa masih di masa depan
                    </small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        
        // Hitung dan preview tanggal batas sewa otomatis
        function hitungBatasSewa() {
            let tanggalSewa = $('#tanggal_sewa').val();
            let lamaSewa = parseInt($('#lama_sewa').val()) || 0;
            
            if (tanggalSewa && lamaSewa > 0) {
                let date = new Date(tanggalSewa);
                date.setMonth(date.getMonth() + lamaSewa);
                
                let day = String(date.getDate()).padStart(2, '0');
                let month = String(date.getMonth() + 1).padStart(2, '0');
                let year = date.getFullYear();
                
                let formattedDate = day + '-' + month + '-' + year;
                $('#tanggal_batas_sewa_preview').text(formattedDate);
                
                // Cek apakah akan aktif atau tidak
                let now = new Date();
                if (date > now) {
                    $('#tanggal_batas_sewa_preview').removeClass('text-danger').addClass('text-success');
                } else {
                    $('#tanggal_batas_sewa_preview').removeClass('text-success').addClass('text-danger');
                }
            }
        }

        // Trigger saat tanggal sewa atau lama sewa berubah
        $('#tanggal_sewa, #lama_sewa').on('change keyup', function() {
            hitungBatasSewa();
        });

        // Hitung saat pertama kali load
        hitungBatasSewa();

        $('#form-edit').validate({
            rules: {
                id_penyewa: { required: true },
                id_kamar: { required: true },
                tanggal_sewa: { required: true, date: true },
                lama_sewa: { required: true, number: true, min: 1 }
            },
            messages: {
                id_penyewa: { required: "Penyewa harus dipilih" },
                id_kamar: { required: "Kamar harus dipilih" },
                tanggal_sewa: { required: "Tanggal sewa harus diisi" },
                lama_sewa: { 
                    required: "Lama sewa harus diisi",
                    number: "Lama sewa harus berupa angka",
                    min: "Minimal 1 bulan"
                }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                timer: 2000
                            });
                            dataTransaksiSewa.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function (prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan sistem'
                        });
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
@endempty