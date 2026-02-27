<form action="{{ url('/kamar/store') }}" method="POST" id="form-tambah-kamar">
    @csrf
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah Kamar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- No Kamar --}}
                <div class="form-group">
                    <label>No Kamar</label>
                    <input type="text" name="no_kamar" id="no_kamar" class="form-control" required>
                    <small id="error-no_kamar" class="error-text text-danger"></small>
                </div>

                {{-- Tipe Kamar --}}
                <div class="form-group">
                    <label>Tipe Kamar</label>
                    <select name="id_tipe_kamar" id="id_tipe_kamar" class="form-control" required>
                        <option value="">-- Pilih Tipe Kamar --</option>
                        @foreach ($tipe_kamar as $item)
                            <option value="{{ $item->id_tipe_kamar }}">
                                {{ $item->jenis_tipe_kamar }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_tipe_kamar" class="error-text text-danger"></small>
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="disewa">Disewa</option>
                    </select>
                    <small id="error-status" class="error-text text-danger"></small>
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

    $("#form-tambah-kamar").validate({
        rules: {
            no_kamar: { required: true, maxlength: 3 },
            id_tipe_kamar: { required: true },
            status: { required: true }
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

                        dataKamar.ajax.reload();
                    }
                },
                error: function(xhr) {
                    $('.error-text').text('');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(field, msg) {
                            $("#error-" + field).text(msg[0]);
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Periksa kembali inputan Anda.'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Server Error',
                            text: 'Terjadi kesalahan pada server.'
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

    // Hapus error saat mengetik ulang
    $('#no_kamar, #id_tipe_kamar, #status').on('input change', function() {
        $("#error-" + $(this).attr('id')).text('');
    });

});
</script>