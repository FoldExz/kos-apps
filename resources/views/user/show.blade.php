<div class="modal-dialog" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <h5 class="modal-title">Detail User</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <div class="modal-body">

      <table class="table table-sm table-bordered table-striped">

        {{-- NAMA USER --}}
        <tr>
          <th class="text-right col-4">Nama User</th>
          <td class="col-8">

            @if($user->role === 'penyewa')
                {{ $user->penyewa->nama ?? '-' }}

            @elseif(in_array($user->role,['pemilik','operator']))
                {{ $user->pengelola->nama ?? '-' }}

            @else
                -
            @endif

          </td>
        </tr>

        {{-- USERNAME --}}
        <tr>
          <th class="text-right col-4">Username</th>
          <td>{{ $user->username }}</td>
        </tr>

        {{-- ROLE --}}
        <tr>
          <th class="text-right col-4">Role</th>
          <td>{{ ucfirst($user->role) }}</td>
        </tr>

        {{-- ALAMAT --}}
        <tr>
          <th class="text-right col-4">Alamat</th>
          <td>
            @if($user->role === 'penyewa')
                {{ $user->penyewa->alamat ?? '-' }}

            @elseif(in_array($user->role,['pemilik','operator']))
                {{ $user->pengelola->alamat ?? '-' }}

            @else
                -
            @endif
          </td>
        </tr>

        {{-- NO HP --}}
        <tr>
          <th class="text-right col-4">No HP</th>
          <td>
            @if($user->role === 'penyewa')
                {{ $user->penyewa->no_hp ?? '-' }}

            @elseif(in_array($user->role,['pemilik','operator']))
                {{ $user->pengelola->no_hp ?? '-' }}

            @else
                -
            @endif
          </td>
        </tr>

        {{-- JENIS KELAMIN --}}
        <tr>
          <th class="text-right col-4">Jenis Kelamin</th>
          <td>
            @php
              $jk =
                  $user->role === 'penyewa'
                    ? ($user->penyewa->jenis_kelamin ?? null)
                    : ($user->pengelola->jenis_kelamin ?? null);
            @endphp

            {{ $jk == 'laki-laki' ? 'Laki-laki' : ($jk == 'perempuan' ? 'Perempuan' : '-') }}
          </td>
        </tr>

        {{-- TANGGAL DIBUAT --}}
        <tr>
          <th class="text-right col-4">Dibuat Pada</th>
          <td>{{ $user->created_at }}</td>
        </tr>

      </table>

    </div>

    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-primary" data-dismiss="modal">Tutup</button>
    </div>

  </div>
</div>