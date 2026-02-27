<form action="{{ url('/transaksi_pembayaran/store') }}" 
      method="POST" 
      id="form-tambah-pembayaran" 
      enctype="multipart/form-data">

    @csrf

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Pembayaran Pertama</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- INFO --}}
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Sistem akan otomatis membuat tagihan untuk bulan berikutnya
                </div>

                {{-- TRANSAKSI SEWA --}}
                <div class="form-group">
                    <label>Transaksi Sewa <span class="text-danger">*</span></label>
                    <select name="id_transaksi_sewa" id="id_transaksi_sewa" class="form-control" required>
                        <option value="">-- Pilih Transaksi Sewa --</option>
                        @foreach ($transaksiSewa as $sewa)
                            <option value="{{ $sewa->id_transaksi_sewa }}">
                                {{ $sewa->penyewa->nama }} | Kamar {{ $sewa->kamar->no_kamar }} | {{ $sewa->lama_sewa }} bulan
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_transaksi_sewa" class="error-text text-danger"></small>
                </div>

                {{-- NOMINAL --}}
                <div class="form-group">
                    <label>Nominal Pembayaran <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" id="nominal" class="form-control" 
                           placeholder="Contoh: 500000" required>
                    <small class="text-muted">Minimal 1 bulan sewa</small>
                    <small id="error-nominal" class="error-text text-danger d-block"></small>
                </div>

                {{-- TANGGAL BAYAR --}}
                <div class="form-group">
                    <label>Tanggal Bayar <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_bayar" id="tanggal_bayar" 
                           class="form-control" value="{{ date('Y-m-d') }}" required>
                    <small id="error-tanggal_bayar" class="error-text text-danger d-block"></small>
                </div>

                {{-- BUKTI BAYAR --}}
                <div class="form-group">
                    <label>Bukti Bayar <span class="text-danger">*</span></label>
                    <input type="file" name="bukti_bayar" id="bukti_bayar" 
                           class="form-control-file" accept="image/*,.pdf" required>
                    <small class="text-muted">Format: JPG, PNG, PDF (Max: 2MB)</small>
                    <small id="error-bukti_bayar" class="error-text text-danger d-block"></small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    Batal
                </button>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>

        </div>
    </div>

</form>

<script>
$(document).ready(function() {

    // Validasi & Submit
    $("#form-tambah-pembayaran").validate({
        rules: {
            id_transaksi_sewa: { required: true },
            nominal: { required: true, number: true, min: 1 },
            tanggal_bayar: { required: true },
            bukti_bayar: { required: true }
        },
        submitHandler: function(form, event) {
            event.preventDefault(); // KUNCI UTAMA: Tahan form sekuat tenaga agar tidak pindah halaman

            let btn = $(form).find('button[type="submit"]');
            let oldText = btn.html();
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            var formData = new FormData(form);

            $.ajax({
                url: form.action,
                type: "POST", // Selalu paksa POST
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === true) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        });

                        // Reload DataTable
                        if (typeof dataPembayaran !== 'undefined') {
                            dataPembayaran.ajax.reload();
                        }
                    } else {
                        // Tampilkan error JSON dari controller
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: response.message || 'Periksa kembali data Anda.'
                        });
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText); // Tulis error di console buat debug
                    
                    let errorMsg = 'Terjadi kesalahan sistem.';
                    
                    if(xhr.status === 400) {
                        // NANGKEP PESAN ERROR ASLI DARI CONTROLLER
                        try {
                            let response = JSON.parse(xhr.responseText);
                            errorMsg = response.message || 'Bad Request (400): Periksa aturan validasi khusus di controller.';
                        } catch (e) {
                            errorMsg = 'Bad Request (400): Data gagal diproses.';
                        }
                    } else if (xhr.status === 413) {
                        errorMsg = 'Payload Too Large (413): File yang Anda unggah terlalu besar.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!', // Ubah ke Gagal biar lebih rapi
                        text: errorMsg
                    });
                },
                complete: function() {
                    btn.prop('disabled', false).html(oldText);
                }
            });
            
            return false; // Mencegah form tersubmit secara native
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