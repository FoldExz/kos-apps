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
                <h5><i class="icon fas fa-ban"></i> Data Tidak Ditemukan</h5>
                Data tipe kamar tidak tersedia.
            </div>
        </div>
    </div>
</div>
@else

<form action="{{ url('/tipe_kamar/' . $tipeKamar->id_tipe_kamar . '/update') }}" 
      method="POST" id="form-edit">
    @csrf
    @method('PUT')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">Edit Tipe Kamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <!-- BODY -->
            <div class="modal-body">
                
                <div class="form-group">
                    <label>Nama Tipe</label>
                    <input type="text" name="jenis_tipe_kamar" class="form-control"
                        value="{{ $tipeKamar->jenis_tipe_kamar }}" required>
                    <small id="error-jenis_tipe_kamar" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Harga / Bulan</label>
                    <input type="number" name="harga_perbulan" class="form-control"
                        value="{{ $tipeKamar->harga_perbulan }}" required>
                    <small id="error-harga_perbulan" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Fasilitas</label>
                    <textarea name="fasilitas" class="form-control" required>{{ $tipeKamar->fasilitas }}</textarea>
                    <small id="error-fasilitas" class="error-text text-danger"></small>
                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>

        </div>
    </div>

</form>

<script>
$(document).ready(function () {

    $("#form-edit").validate({

        rules: {
            jenis_tipe_kamar: { required: true },
            harga_perbulan: { required: true, number: true, min: 0 },
            fasilitas: { required: true }
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
                            text: response.message
                        });

                        dataTipeKamar.ajax.reload(); // reload datatable

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