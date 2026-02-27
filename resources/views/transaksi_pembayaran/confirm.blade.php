@empty($pembayaran)
<div id="modal-master" class="modal-dialog modal-md" role="document">
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
                Data transaksi pembayaran tidak ditemukan.
            </div>

            <a href="{{ url('/transaksi_pembayaran') }}" class="btn btn-warning btn-sm">Kembali</a>
        </div>

    </div>
</div>
@else

<form action="{{ url('/transaksi_pembayaran/' . $pembayaran->id_transaksi_pembayaran . '/delete') }}" 
      method="POST" id="form-delete-pembayaran">
    @csrf
    @method('DELETE')

    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Hapus Transaksi Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="alert alert-warning">
                    <h5><i class="icon fas fa-exclamation-triangle"></i> Konfirmasi !!!</h5>
                    Apakah Anda yakin ingin menghapus transaksi pembayaran berikut?
                </div>

                <table class="table table-sm table-bordered table-striped">

                    <tr>
                        <th class="text-right col-4">ID Pembayaran:</th>
                        <td>{{ $pembayaran->id_transaksi_pembayaran }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Penyewa:</th>
                        <td>{{ $pembayaran->transaksiSewa->penyewa->nama ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Kamar:</th>
                        <td>{{ $pembayaran->transaksiSewa->kamar->no_kamar ?? '-' }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Uraian:</th>
                        <td>{{ $pembayaran->uraian }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Nominal:</th>
                        <td>Rp {{ number_format($pembayaran->nominal,0,',','.') }}</td>
                    </tr>

                    <tr>
                        <th class="text-right">Tanggal Jatuh Tempo:</th>
                        <td>
                            {{ $pembayaran->tanggal_jatuh_tempo 
                                ? date('d-m-Y', strtotime($pembayaran->tanggal_jatuh_tempo))
                                : '-' }}
                        </td>
                    </tr>

                    <tr>
                        <th class="text-right">Status:</th>
                        <td>{{ strtoupper($pembayaran->status) }}</td>
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

    $("#form-delete-pembayaran").validate({

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

                        dataPembayaran.ajax.reload();

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
                        text: 'Tidak dapat menghapus pembayaran yang sudah lunas!'
                    });
                }

            });

            return false;
        }

    });

});
</script>

@endempty