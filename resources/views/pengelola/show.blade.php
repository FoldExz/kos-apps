<div class="modal-dialog" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">Detail Pengelola</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <table class="table table-sm table-bordered table-striped">

        <tr>
          <th class="text-right col-4">Nama Pengelola</th>
          <td class="col-8">{{ $pengelola->nama }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">No HP</th>
          <td>{{ $pengelola->no_hp }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Alamat</th>
          <td>{{ $pengelola->alamat }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Jenis Kelamin</th>
          <td>
            {{ $pengelola->jenis_kelamin == 'laki-laki'
                ? 'Laki-laki'
                : ($pengelola->jenis_kelamin == 'perempuan' ? 'Perempuan' : '-') 
            }}
          </td>
        </tr>

        <tr>
          <th class="text-right col-4">Tanggal Dibuat</th>
          <td>{{ $pengelola->created_at }}</td>
        </tr>

      </table>
    </div>

    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
    </div>

  </div>
</div>