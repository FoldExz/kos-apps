@extends('layouts.template')

@section('title')
| Daftar User
@endsection

@section('content')
<section class="section">
  <div class="section-header">
    <h1>{{ $breadcrumb->title ?? 'Daftar User' }}</h1>
    @include('layouts.breadcrumb', ['list' => $breadcrumb->list ?? ['Home', 'User']])
  </div>

  <div class="section-body">
    <div class="card">
      <div class="card-header">
        <h4>Data User</h4>
        <div class="card-header-action ml-auto">
            <button onclick="modalAction(`{{ url('/user/create') }}`)" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah User
            </button>
        </div>
      </div>

      <div class="card-body">
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover dt-responsive nowrap" id="table_user" style="width:100%">
            <thead class="thead-light">
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Role</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              {{-- DataTables --}}
            </tbody>
          </table>
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
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
  });

  function modalAction(url = '') {
    $('#myModal').empty();
    $('#myModal').load(url, function () {
      $('#myModal').modal('show');
    });
  }

  var dataUser;
  $(document).ready(function () {

    dataUser = $('#table_user').DataTable({
      processing: true,
      serverSide: true,
      responsive: true,
      ajax: {
        url: "{{ url('/user/list') }}",
        type: "POST"
      },
      columns: [
        { data: 'DT_RowIndex', className: 'text-center', orderable: false, searchable: false },
        { data: 'nama', className: '' },
        { data: 'username', className: 'text-center' },
        { data: 'role', className: 'text-center' },
        { data: 'aksi', className: 'text-center', orderable: false, searchable: false }
      ]
    });
    $('#myModal').on('hidden.bs.modal', function () {
        $(this).empty();
    });

  });
</script>
@endpush