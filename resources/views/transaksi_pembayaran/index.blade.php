@extends('layouts.template')

@section('title')
| Transaksi Pembayaran
@endsection

@push('css')
{{-- Tambahkan CSS DataTables jika diperlukan --}}
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Transaksi Pembayaran' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list])
  </div>

  <div class="section-body">
    <div class="col-12">
      <div class="card">

        <div class="card-header">
          <h4>{{ $page->title ?? 'Data Pembayaran Sewa' }}</h4>
          <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/transaksi_pembayaran/create') }}`)" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped dt-responsive nowrap"
                   id="table_pembayaran"
                   style="width:100%;">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Penyewa</th>
                  <th>Kamar</th>
                  <th>Tanggal Bayar</th>
                  <th>Nominal</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- data dimuat via DataTables --}}
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer text-right">
          {{-- Pagination otomatis oleh DataTables --}}
        </div>

      </div>
    </div>
  </div>
</section>

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

  var dataPembayaran;

  $(document).ready(function () {
    dataPembayaran = $('#table_pembayaran').DataTable({
      processing: false,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/transaksi_pembayaran/list') }}",
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
            data: 'penyewa_nama',
            orderable: true,
            searchable: true
        },
        { 
            data: 'kamar_no',
            orderable: true,
            searchable: true
        },
        { 
            data: 'tanggal_bayar',
            className: 'text-center'
        },
        { 
            data: 'nominal',
            className: 'text-center'
        },
        {
            data: 'status_badge',
            className: 'text-center',
            orderable: true,
            searchable: true
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