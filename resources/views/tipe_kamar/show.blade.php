@extends('layouts.template')

@section('title')
| Data Tipe Kamar
@endsection

@push('css')
{{-- Tambahkan CSS tambahan jika diperlukan --}}
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Data Tipe Kamar' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list])
  </div>

  <div class="section-body">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Tipe Kamar</h4>
          <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/tipe_kamar/create') }}`)" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped dt-responsive nowrap" id="table_tipe_kamar" style="width:100%">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>Jenis Tipe Kamar</th>
                  <th>Harga / Bulan</th>
                  <th>Fasilitas</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                {{-- Data dimuat dinamis melalui DataTables --}}
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

  var dataTipeKamar;

  $(document).ready(function () {
    dataTipeKamar = $('#table_tipe_kamar').DataTable({
      processing: false,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/tipe_kamar/list') }}",
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
          data: 'jenis_tipe_kamar',
          orderable: true,
          searchable: true
        },
        {
          data: 'harga_perbulan',
          className: 'text-right',
          orderable: true,
          searchable: true,
          render: function(data){
            return 'Rp ' + parseInt(data).toLocaleString('id-ID');
          }
        },
        {
          data: 'fasilitas',
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