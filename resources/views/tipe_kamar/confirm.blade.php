@empty($tipeKamar)
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
                    <h5><i class="icon fas fa-ban"></i> Kesalahan !!!</h5>
                    Data tipe kamar tidak ditemukan.
                </div>

                <a href="{{ url('/tipe_kamar') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else

<form action="{{ url('/tipe_kamar/' . $tipeKamar->id_tipe_kamar . '/delete') }}" 
      method="POST" id="form-delete">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Hapus Tipe Kamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus tipe kamar berikut?
                </div>

                <table class="table table-sm table-bordered table-striped">

                    <tr>
                        <th class="text-right col-4">ID Tipe Kamar:</th>
                        <td class="col-9">{{ $tipeKamar->id_tipe_kamar }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Nama Tipe:</th>
                        <td class="col-9">{{ $tipeKamar->jenis_tipe_kamar }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Harga / Bulan:</th>
                        <td class="col-9">Rp {{ number_format($tipeKamar->harga_perbulan, 0, ',', '.') }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Fasilitas:</th>
                        <td class="col-9">{{ $tipeKamar->fasilitas }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Tanggal Dibuat:</th>
                        <td class="col-9">{{ $tipeKamar->created_at }}</td>
                    </tr>

                </table>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Ya, Hapus</button>
            </div>

        </div>
    </div>

</form>

<script>
$(document).ready(function() {

    $("#form-delete").validate({

        rules: {},

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

                        dataTipeKamar.ajax.reload(); // reload datatable tipe kamar

                    } else {

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
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server.'
                    });
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
</script>

@endempty