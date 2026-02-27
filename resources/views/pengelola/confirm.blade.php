@empty($pengelola)
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                Data pengelola tidak ditemukan.
            </div>
            <a href="{{ url('/pengelola') }}" class="btn btn-warning">Kembali</a>
        </div>
    </div>
</div>
@else

<form action="{{ url('/pengelola/' . $pengelola->id_pemilik . '/delete') }}" method="POST" id="form-delete">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Hapus Pengelola</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-ban"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus data pengelola berikut?
                </div>

                <table class="table table-sm table-bordered table-striped">

                    <tr>
                        <th class="text-right col-4">ID Pengelola:</th>
                        <td class="col-9">{{ $pengelola->id_pemilik }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Nama:</th>
                        <td class="col-9">{{ $pengelola->nama }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Alamat:</th>
                        <td class="col-9">{{ $pengelola->alamat }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">No HP:</th>
                        <td class="col-9">{{ $pengelola->no_hp }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Jenis Kelamin:</th>
                        <td class="col-9">{{ ucfirst($pengelola->jenis_kelamin) }}</td>
                    </tr>

                    <tr>
                        <th class="text-right col-4">Tanggal Dibuat:</th>
                        <td class="col-9">{{ $pengelola->created_at }}</td>
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

                            dataPengelola.ajax.reload(); // reload datatable
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