@empty($transaksi)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-danger">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan !!!</h5>
                    Data transaksi tidak ditemukan.
                </div>

                <a href="{{ url('/transaksi_sewa') }}" class="btn btn-warning btn-sm">Kembali</a>
            </div>

        </div>
    </div>
@else

<form action="{{ url('/transaksi_sewa/' . $transaksi->id_transaksi_sewa . '/delete') }}" 
      method="POST" id="form-delete-transaksi">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Hapus Transaksi</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus transaksi berikut?
                </div>

                <table class="table table-sm table-bordered table-striped">

                    <tr>
                        <th class="text-right col-4">ID Transaksi:</th>
                        <td>{{ $transaksi->id_transaksi_sewa }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Penyewa:</th>
                        <td>{{ $transaksi->penyewa->nama ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Kamar:</th>
                        <td>{{ $transaksi->kamar->no_kamar ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Tanggal Sewa:</th>
                        <td>{{ $transaksi->tanggal_sewa }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Tanggal Batas Sewa:</th>
                        <td>{{ $transaksi->tanggal_batas_sewa }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Status:</th>
                        <td>{{ ucfirst($transaksi->status) }}</td>
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
$(document).ready(function () {

    $("#form-delete-transaksi").validate({

        rules: {},

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
                            text: response.message
                        });

                        dataTransaksi.ajax.reload();

                    } else {
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
                        title: 'Server Error',
                        text: 'Terjadi kesalahan pada server.'
                    });
                }

            });

            return false;
        }

    });

});
</script>

@endempty