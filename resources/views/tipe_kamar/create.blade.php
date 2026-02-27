<form action="{{ url('/tipe_kamar/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Tipe Kamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Jenis Tipe Kamar</label>
                    <input type="text" name="jenis_tipe_kamar" id="jenis_tipe" class="form-control" required>
                    <small id="error-jenis_tipe_kamar" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Harga / Bulan</label>
                    <input type="number" name="harga_perbulan" id="harga" class="form-control" required>
                    <small id="error-harga_perbulan" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Fasilitas</label>
                    <textarea name="fasilitas" id="fasilitas" class="form-control" required></textarea>
                    <small id="error-fasilitas" class="error-text text-danger"></small>
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
            jenis_tipe: { required: true },
            harga: { required: true, number: true, min: 0 },
            fasilitas: { required: true },
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
                        dataTipeKamar.ajax.reload();
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
</script>