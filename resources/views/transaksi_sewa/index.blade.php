@extends('layouts.template')

@section('title')
| Transaksi Sewa
@endsection

@push('css')
{{-- Tambahkan CSS DataTables jika diperlukan --}}
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Transaksi Sewa' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list])
  </div>

  <div class="section-body">
    <div class="col-12">
      <div class="card">

        <div class="card-header">
          <h4>{{ $page->title ?? 'Data Transaksi' }}</h4>
          <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/transaksi_sewa/create') }}`)" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped dt-responsive nowrap" 
                   id="table_transaksi"
                   style="width:100%;">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Nama Penyewa</th>
                  <th>Kamar</th>
                  <th>Tanggal Sewa</th>
                  <th>Lama Sewa (bulan)</th>
                  <th>Batas Sewa</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- Data dimuat via DataTables --}}
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

  var dataTransaksi;

  $(document).ready(function () {
    dataTransaksi = $('#table_transaksi').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/transaksi_sewa/list') }}",
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
          data: 'kamar_nama',
          orderable: true,
          searchable: true
        },
        { data: 'tanggal_sewa', 
          className: 'text-center' ,
        },
        { data: 'lama_sewa', 
          className: 'text-center' 
        },
        { data: 'tanggal_batas_sewa', 
          className: 'text-center' ,
          
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