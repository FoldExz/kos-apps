@empty($penyewa)
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
                Data penyewa tidak tersedia.
            </div>
        </div>
    </div>
</div>
@else

<form action="{{ url('/penyewa/' . $penyewa->id_penyewa . '/update') }}" method="POST" id="form-edit">
    @csrf
    @method('PUT')

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Data Penyewa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label>Nama Penyewa</label>
                    <input type="text" name="nama" class="form-control" 
                        value="{{ $penyewa->nama }}" required>
                    <small id="error-name" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>No HP</label>
                    <input type="text" name="no_hp" class="form-control" 
                        value="{{ $penyewa->no_hp }}" required>
                    <small id="error-no_hp" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" required>{{ $penyewa->alamat }}</textarea>
                    <small id="error-alamat" class="error-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="laki-laki" {{ $penyewa->jenis_kelamin == 'laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="perempuan" {{ $penyewa->jenis_kelamin == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    <small id="error-jenis_kelamin" class="error-text text-danger"></small>
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

        $('#form-edit').validate({
            rules: {
                nama: { required: true },
                no_hp: { required: true, minlength: 1,maxlength: 13 },
                alamat: { required: true },
                jenis_kelamin: { required: true }
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

                            dataPenyewa.ajax.reload(); // reload datatable
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

    });
</script>

@endempty