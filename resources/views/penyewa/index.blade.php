@extends('layouts.template')

@section('title')
| Data Penyewa
@endsection

@push('css')
{{-- Tambahkan CSS tambahan jika diperlukan --}}
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Data Penyewa' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list])
  </div>

  <div class="section-body">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Penyewa</h4>
          <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/penyewa/create') }}`)" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped dt-responsive nowrap" id="table_penyewa" style="width:100%">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Nama</th>
                  <th>Alamat</th>
                  <th>No HP</th>
                  <th>Jenis Kelamin</th>
                  <th>File KTP</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- Data dimuat dinamis oleh DataTables --}}
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer text-right"></div>
      </div>
    </div>
  </div>
</section>

{{-- Modal --}}
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true"></div>

@endsection

@push('js')
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function modalAction(url = '') {
    $('#myModal').load(url, function () {
      $('#myModal').modal('show');
    });
  }

  var dataPenyewa;

  $(document).ready(function () {
    dataPenyewa = $('#table_penyewa').DataTable({
      processing: false,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/penyewa/list') }}",
        type: "POST"
      },
      columns: [
        {
          data: 'DT_RowIndex',
          className: 'text-center',
          orderable: false,
          searchable: false
        },
        {
          data: 'nama',
          orderable: true,
          searchable: true
        },
        {
          data: 'alamat',
          orderable: true,
          searchable: true
        },
        {
          data: 'no_hp',
          className: 'text-center',
          orderable: true,
          searchable: true
        },
        {
          data: 'jenis_kelamin',
          className: 'text-center',
          orderable: true,
          searchable: true
        },
        {
            data: 'foto_ktp',
            className: '',
            render: function (data, type, row) {
              if (!data) return '-';

              // Ambil nama file dari path
              const fileName = data.split('/').pop().replace(/^\d{10}_/, '');

              return `
                <a href="/storage/${data}" download="${fileName}" title="Klik untuk download">
                  ${fileName}
                </a>
              `;
            }
          },
        {
          data: 'aksi',
          className: 'text-center',
          orderable: false,
          searchable: false
        }
      ]
    });
  });
</script>
@endpush