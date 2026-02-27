@empty($pembayaran)
<div id="modal-master" class="modal-dialog modal-md" role="document">
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
                Data pembayaran tidak tersedia.
            </div>
        </div>
    </div>
</div>
@else

<form action="{{ url('/transaksi_pembayaran/' . $pembayaran->id_transaksi_pembayaran . '/update') }}" 
      method="POST" enctype="multipart/form-data" id="form-edit">
    @csrf
    @method('PUT')

    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">
                    <i class="fas fa-money-check"></i> Bayar Tagihan
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <!-- Informasi pembayaran -->
                <div class="alert alert-info mb-3">
                    <strong>Penyewa:</strong> {{ $pembayaran->transaksiSewa->penyewa->nama }} <br>
                    <strong>Kamar:</strong> {{ $pembayaran->transaksiSewa->kamar->no_kamar }} <br>
                    <strong>Uraian:</strong> {{ $pembayaran->uraian }} <br>
                    <strong>Jatuh Tempo:</strong> 
                    {{ $pembayaran->tanggal_jatuh_tempo 
                        ? date('d-m-Y', strtotime($pembayaran->tanggal_jatuh_tempo)) 
                        : '-' }}
                </div>

                <div class="form-group">
                    <label>Nominal Pembayaran <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" class="form-control" 
                           value="{{ $pembayaran->nominal }}" required>
                    <small id="error-nominal" class="form-text text-danger error-text"></small>
                </div>

                <div class="form-group">
                    <label>Bukti Bayar (jpg/jpeg/png/pdf) <span class="text-danger">*</span></label>
                    <input type="file" name="bukti_bayar" class="form-control">
                    <small id="error-bukti_bayar" class="form-text text-danger error-text"></small>

                    @if($pembayaran->bukti_bayar)
                        <small class="text-muted">
                            File sebelumnya: <a href="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" target="_blank">Lihat</a>
                        </small>
                    @endif
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Batal
                </button>

                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Simpan Pembayaran
                </button>
            </div>

        </div>
    </div>
</form>

<script>
$(document).ready(function () {

    $('#form-edit').validate({
        rules: {
            nominal: { required: true, number: true, min: 1 },
            bukti_bayar: { 
                required: false, 
                extension: "jpg|jpeg|png|pdf",
                filesize: 2048 * 1024
            }
        },
        messages: {
            nominal: {
                required: "Nominal harus diisi",
                number: "Nominal harus berupa angka",
                min: "Minimal nominal 1"
            },
            bukti_bayar: {
                extension: "Format file harus jpg, jpeg, png, atau pdf",
                filesize: "Ukuran maksimal 2MB"
            }
        },

        submitHandler: function (form) {
            let formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                contentType: false,
                processData: false,

                success: function (response) {
                    if (response.status) {
                        $('#myModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 2000
                        });

                        dataPembayaran.ajax.reload();
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

                error: function () {
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

    // Custom rule: file size
    $.validator.addMethod("filesize", function (value, element, param) {
        return this.optional(element) || (element.files[0].size <= param);
    });

});
</script>

@endempty