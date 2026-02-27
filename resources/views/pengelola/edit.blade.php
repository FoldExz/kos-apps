@empty($user)
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">Data user tidak ditemukan.</div>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/user/'.$user->id_user.'/update') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-md" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title text-primary">Edit User</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">

                    {{-- USERNAME --}}
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="{{ $user->username }}">
                        <small id="error-username" class="error-text text-danger"></small>
                    </div>

                    {{-- ROLE --}}
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="role-edit" class="form-control">
                            <option value="">-- Pilih Role --</option>
                            <option value="pemilik" {{ $user->role == 'pemilik' ? 'selected' : '' }}>Pemilik</option>
                            <option value="operator" {{ $user->role == 'operator' ? 'selected' : '' }}>Operator</option>
                            <option value="penyewa" {{ $user->role == 'penyewa' ? 'selected' : '' }}>Penyewa</option>
                        </select>
                        <small id="error-role" class="error-text text-danger"></small>
                    </div>

                    {{-- PENGELOLA --}}
                    <div class="form-group" id="form-pengelola-edit" style="display: {{ in_array($user->role, ['pemilik', 'operator']) ? 'block' : 'none' }};">
                        <label>Pilih Pengelola</label>
                        <select name="id_pengelola" class="form-control">
                            <option value="">-- Pilih User --</option>
                            @foreach ($pengelola as $p)
                                <option value="{{ $p->id_pemilik }}" {{ $user->id_pengelola == $p->id_pemilik ? 'selected' : '' }}>
                                    {{ $p->nama_pengelola ?? $p->nama_pemilik ?? $p->nama ?? 'Tanpa Nama' }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-id_pengelola" class="error-text text-danger"></small>
                    </div>

                    {{-- PENYEWA --}}
                    <div class="form-group" id="form-penyewa-edit" style="display: {{ $user->role == 'penyewa' ? 'block' : 'none' }};">
                        <label>Pilih Penyewa</label>
                        <select name="id_penyewa" class="form-control">
                            <option value="">-- Pilih User --</option>
                            @foreach ($penyewa as $p)
                                <option value="{{ $p->id_penyewa }}" {{ $user->id_penyewa == $p->id_penyewa ? 'selected' : '' }}>
                                    {{ $p->nama_penyewa ?? $p->nama ?? 'Tanpa Nama' }}
                                </option>
                            @endforeach
                        </select>
                        <small id="error-id_penyewa" class="error-text text-danger"></small>
                    </div>

                    {{-- PASSWORD --}}
                    <div class="form-group">
                        <label>Password (Kosongkan jika tidak ingin diubah)</label>
                        <input type="password" name="password" id="password-field" class="form-control">
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
                    <button type="button" id="btn-update" class="btn btn-primary btn-sm">Simpan Perubahan</button>
                </div>

            </div>
        </div>
    </form>

    <script>
    $(document).ready(function() {
        // Logic Sembunyikan Form Role
        $('#role-edit').on('change', function() {
            let role = $(this).val();
            $('#form-pengelola-edit').hide();
            $('#form-penyewa-edit').hide();

            if (role === 'pemilik' || role === 'operator') {
                $('#form-pengelola-edit').show();
            } else if (role === 'penyewa') {
                $('#form-penyewa-edit').show();
            }
        });

        // Logic Submit AJAX (Sama dengan create, anti-GET)
        $('#btn-update').on('click', function(e) {
            e.preventDefault();
            
            let form = $('#form-edit');
            let btn = $(this);
            let oldText = btn.text();
            
            btn.prop('disabled', true).text('Menyimpan...');
            $('.error-text').text(''); 

            $.ajax({
                url: form.attr('action'),
                type: "POST", // Tetap POST karena dibelakang ada @method('PUT')
                data: form.serialize(),
                success: function(res) {
                    if (res.status === true) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: res.message
                        });
                        
                        if(typeof dataUser !== 'undefined') {
                            dataUser.ajax.reload(); 
                        }
                    } else {
                        $.each(res.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'Terjadi kesalahan sistem: ' + error, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text(oldText);
                }
            });
        });
    });
    </script>
@endempty