@empty($kamar)
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
                    <h5><i class="icon fas fa-ban"></i> Data Tidak Ditemukan</h5>
                    Data kamar tidak tersedia.
                </div>
            </div>
        </div>
    </div>
@else
<form action="{{ url('/kamar/' . $kamar->id_kamar . '/update') }}" method="POST" id="form-edit-kamar">
    @csrf
    @method('PUT')

    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Edit Kamar</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- No Kamar --}}
                <div class="form-group">
                    <label>No Kamar</label>
                    <input type="text" name="no_kamar" id="no_kamar"
                           value="{{ $kamar->no_kamar }}"
                           class="form-control" required>
                    <small id="error-no_kamar" class="error-text text-danger"></small>
                </div>

                {{-- Tipe Kamar --}}
                <div class="form-group">
                    <label>Tipe Kamar</label>
                    <select name="id_tipe_kamar" id="id_tipe_kamar" class="form-control" required>
                        <option value="">-- Pilih Tipe Kamar --</option>
                        @foreach ($tipe as $item)
                            <option value="{{ $item->id_tipe_kamar }}"
                                {{ $kamar->id_tipe_kamar == $item->id_tipe_kamar ? 'selected' : '' }}>
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
                        <option value="tersedia" {{ $kamar->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="disewa" {{ $kamar->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                    </select>
                    <small id="error-status" class="error-text text-danger"></small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
            </div>

        </div>
    </div>
</form>


<script>
$(document).ready(function () {

    $('#form-edit-kamar').validate({
        rules: {
            no_kamar: { required: true, maxlength: 3 },
            id_tipe_kamar: { required: true },
            status: { required: true },
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

                        dataKamar.ajax.reload();
                    }
                },

                error: function (xhr) {
                    $('.error-text').text('');

                    // VALIDASI LARAVEL 422
                    if (xhr.status === 422 && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function (field, messages) {
                            $('#error-' + field).text(messages[0]);
                        });

                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            text: 'Silakan periksa input Anda.'
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

    // Bersihkan error saat input berubah
    $('#no_kamar, #id_tipe_kamar, #status').on('input change', function () {
        $('#error-' + $(this).attr('id')).text('');
    });

});
</script>

@endempty