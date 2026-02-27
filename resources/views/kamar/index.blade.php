@extends('layouts.template')

@section('title')
| Data Kamar
@endsection

@push('css')
{{-- Tambahkan CSS tambahan jika diperlukan --}}
@endpush

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Data Kamar' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list])
  </div>

  <div class="section-body">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h4>Data Kamar</h4>
          <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/kamar/create') }}`)" class="btn btn-primary">
              <i class="fas fa-plus"></i> Tambah
            </button>
          </div>
        </div>

        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover table-striped dt-responsive nowrap" id="table_kamar" style="width:100%">
              <thead class="thead-light">
                <tr>
                  <th>#</th>
                  <th>No Kamar</th>
                  <th>Tipe Kamar</th>
                  <th>Status</th>
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

  var dataKamar;

  $(document).ready(function () {
    dataKamar = $('#table_kamar').DataTable({
      processing: false,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/kamar/list') }}",
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
          data: 'no_kamar',
          orderable: true,
          searchable: true
        },
        {
          data: 'tipe_kamar',
          orderable: true,
          searchable: true
        },
        {
          data: 'status',
          className: 'text-center',
          orderable: true,
          searchable: false,
          render: function(data){
            if(data == 'kosong'){
              return `<span class="badge badge-success">Kosong</span>`;
            } else if(data == 'terisi'){
              return `<span class="badge badge-danger">Terisi</span>`;
            } else {
              return data;
            }
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