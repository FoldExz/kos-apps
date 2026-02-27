<form action="{{ url('/user/store') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-md" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title text-primary">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                {{-- USERNAME --}}
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" class="form-control">
                    <small id="error-username" class="error-text text-danger"></small>
                </div>

                {{-- ROLE --}}
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" id="role" class="form-control">
                        <option value="">-- Pilih Role --</option>
                        <option value="pemilik">Pemilik</option>
                        <option value="operator">Operator</option>
                        <option value="penyewa">Penyewa</option>
                        <option value="admin">Admin Pusat (Bisa lihat semua)</option>
                        <option value="skpd">Orang Dinas / SKPD</option>
                    </select>
                    <small id="error-role" class="error-text text-danger"></small>
                </div>

                {{-- PENGELOLA (pemilik & operator) --}}
                <div class="form-group" id="form-pengelola" style="display:none;">
                    <label>Pilih Pengelola</label>
                    <select name="id_pengelola" class="form-control">
                        <option value="">-- Pilih User --</option>
                        @foreach ($pengelola as $p)
                            <option value="{{ $p->id_pemilik }}">
                                {{ $p->nama_pengelola ?? $p->nama_pemilik ?? $p->nama ?? 'Tanpa Nama' }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_pengelola" class="error-text text-danger"></small>
                </div>
    
                {{-- PENYEWA --}}
                <div class="form-group" id="form-penyewa" style="display:none;">
                    <label>Pilih Penyewa</label>
                    <select name="id_penyewa" class="form-control">
                        <option value="">-- Pilih User --</option>
                        @foreach ($penyewa as $p)
                            <option value="{{ $p->id_penyewa }}">
                                {{ $p->nama_penyewa ?? $p->nama ?? 'Tanpa Nama' }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-id_penyewa" class="error-text text-danger"></small>
                </div>

                {{-- SKPD --}}
                <div class="form-group" id="skpd_group" style="display:none;">
                    <label>Pilih Dinas</label>
                    <select name="kd_skpd" id="kd_skpd" class="form-control">
                        <option value="">-- Pilih Instansi/Dinas --</option>
                        @foreach ($skpd as $s)
                            <option value="{{ $s->kd_skpd }}">
                                {{ $s->nm_skpd }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kd_skpd" class="error-text text-danger"></small>
                </div>


                {{-- PASSWORD --}}
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" id="password-field" class="form-control">
                    <small id="error-password" class="error-text text-danger"></small>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
                <button type="submit" id="btn-simpan" class="btn btn-primary btn-sm">Simpan</button>
            </div>

        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    // 1. Logic Sembunyikan Form Role
    $('#role').on('change', function() {
        let role = $(this).val();
        $('#form-pengelola').hide();
        $('#form-penyewa').hide();
        $('#skpd_group').hide();

        if (role === 'pemilik' || role === 'operator') {
            $('#form-pengelola').show();
        } else if (role === 'penyewa') {
            $('#form-penyewa').show();
        } else if (role === 'skpd') {
            $('#skpd_group').show();
        }
    });

    // 2. TANGKAP EVENT SUBMIT FORM (Anti-Enter Keyboard & Anti Pindah Halaman)
    $('#form-tambah').off('submit').on('submit', function(e) {
        e.preventDefault(); // KUNCI UTAMA: Cegah form pindah halaman
        
        let form = $(this);
        let btn = form.find('button[type="submit"]');
        let oldText = btn.text();
        
        btn.prop('disabled', true).text('Menyimpan...');
        $('.error-text').text(''); // Bersihkan pesan error lama

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            success: function(res) {
                if (res.status === true) {
                    $('#myModal').modal('hide'); // Tutup modal
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: res.message
                    });
                    
                    // Refresh tabel otomatis
                    if(typeof dataUser !== 'undefined') {
                        dataUser.ajax.reload(); 
                    }
                } else {
                    // Tampilkan error validasi di bawah input
                    $.each(res.msgField, function(prefix, val) {
                        $('#error-' + prefix).text(val[0]);
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Terjadi kesalahan sistem: ' + error, 'error');
            },
            complete: function() {
                // Kembalikan kondisi tombol
                btn.prop('disabled', false).text(oldText);
            }
        });
    });
});
</script>