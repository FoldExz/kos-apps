<div class="modal-dialog" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">Detail Penyewa</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <table class="table table-sm table-bordered table-striped">

        <tr>
          <th class="text-right col-4">Nama Penyewa</th>
          <td class="col-8">{{ $penyewa->nama}}</td>
        </tr>

        <tr>
          <th class="text-right col-4">No HP</th>
          <td>{{ $penyewa->no_hp }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Alamat</th>
          <td>{{ $penyewa->alamat }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Jenis Kelamin</th>
          <td>{{ $penyewa->jenis_kelamin == 'laki-laki' ? 'Laki-laki' : ($penyewa->jenis_kelamin == 'perempuan' ? 'Perempuan' : '-') }}</td>
        </tr>

      </table>
    </div>

    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
    </div>

  </div>
</div>