{{-- <div class="modal-dialog" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">Detail Transaksi</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">
      <table class="table table-sm table-bordered table-striped">

        <tr>
          <th class="text-right col-4">ID Transaksi</th>
          <td class="col-8">{{ $transaksi->id_transaksi }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">ID Penyewa</th>
          <td>{{ $transaksi->id_penyewa }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">ID Kamar</th>
          <td>{{ $transaksi->id_kamar }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Tanggal Masuk</th>
          <td>{{ date('d-m-Y', strtotime($transaksi->tanggal_masuk)) }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Tanggal Keluar</th>
          <td>{{ date('d-m-Y', strtotime($transaksi->tanggal_keluar)) }}</td>
        </tr>

        <tr>
          <th class="text-right col-4">Tanggal Bayar</th>
          <td>
            @if($transaksi->tanggal_bayar)
              {{ date('d-m-Y', strtotime($transaksi->tanggal_bayar)) }}
            @else
              -
            @endif
          </td>
        </tr>

        <tr>
          <th class="text-right col-4">Status</th>
          <td>
            @if($transaksi->status == 'lunas')
                <span class="badge badge-success">Lunas</span>
            @else
                <span class="badge badge-warning">Belum Lunas</span>
            @endif
          </td>
        </tr>

      </table>
    </div>

    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
    </div>

  </div>
</div> --}}

@empty($transaksi)
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
                Data transaksi tidak tersedia.
            </div>
        </div>
    </div>
</div>
@else
<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white">Detail Transaksi</h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="modal-body">
            <table class="table table-sm table-bordered table-striped">

                <tr>
                    <th class="text-right col-4">ID Transaksi</th>
                    <td class="col-8">{{ $transaksi->id_transaksi }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Nama Penyewa</th>
                    <td>{{ $transaksi->penyewa->nama ?? '-' }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">No HP Penyewa</th>
                    <td>{{ $transaksi->penyewa->no_hp ?? '-' }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Alamat Penyewa</th>
                    <td>{{ $transaksi->penyewa->alamat ?? '-' }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Nomor Kamar</th>
                    <td>{{ $transaksi->kamar->no_kamar ?? '-' }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Tipe Kamar</th>
                    <td>{{ $transaksi->kamar->tipeKamar->jenis_tipe_kamar ?? '-' }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Harga per Bulan</th>
                    <td>Rp {{ number_format($transaksi->kamar->tipeKamar->harga_perbulan ?? 0, 0, ',', '.') }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Tanggal Masuk</th>
                    <td>{{ date('d F Y', strtotime($transaksi->tanggal_masuk)) }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Tanggal Keluar</th>
                    <td>{{ date('d F Y', strtotime($transaksi->tanggal_keluar)) }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Durasi Sewa</th>
                    <td>
                        @php
                            $masuk = new DateTime($transaksi->tanggal_masuk);
                            $keluar = new DateTime($transaksi->tanggal_keluar);
                            $durasi = $masuk->diff($keluar);
                            $bulan = $durasi->m + ($durasi->y * 12);
                            $hari = $durasi->d;
                        @endphp
                        {{ $bulan }} Bulan {{ $hari }} Hari
                    </td>
                </tr>

                <tr>
                    <th class="text-right col-4">Tanggal Bayar</th>
                    <td>
                        @if($transaksi->tanggal_bayar)
                            {{ date('d F Y', strtotime($transaksi->tanggal_bayar)) }}
                        @else
                            <span class="text-muted">Belum dibayar</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="text-right col-4">Status Pembayaran</th>
                    <td>
                        @if($transaksi->status == 'lunas')
                            <span class="badge badge-success"><i class="fas fa-check-circle"></i> LUNAS</span>
                        @else
                            <span class="badge badge-warning"><i class="fas fa-clock"></i> BELUM LUNAS</span>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="text-right col-4">Dibuat Pada</th>
                    <td>{{ date('d F Y H:i', strtotime($transaksi->created_at)) }}</td>
                </tr>

                <tr>
                    <th class="text-right col-4">Diupdate Pada</th>
                    <td>{{ date('d F Y H:i', strtotime($transaksi->updated_at)) }}</td>
                </tr>

            </table>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                <i class="fas fa-times"></i> Tutup
            </button>
        </div>

    </div>
</div>
@endempty