@extends('layouts.adminlte')

@section('content')
<div class="container">
    <h3>Data Peserta</h3>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#createModal">Tambah Peserta</button>

    <table class="table table-bordered" id="pesertaTable">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>No HP</th>
                <th>Aksi</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="formCreate" method="POST" action="{{ route('peserta.store') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Peserta</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control"  required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"  required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="formEdit" method="POST">
      @csrf @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Peserta</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="" required>
            </div>
            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="address" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label>No HP</label>
                <input type="text" name="no_hp" class="form-control" value="">
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
    // Load data ke dalam modal edit
    $(document).ready(function () {
        $(document).on('click', '.btn-edit', function () {
            let id = $(this).data('id');
            console.log("Peserta ID: " + id);  // Debug ID peserta

            $.get(`/peserta/${id}/edit`, function (data) {
                // Mengisi data peserta ke dalam modal
                $('#editModal input[name="name"]').val(data.name);
                $('#editModal input[name="email"]').val(data.email);
                $('#editModal textarea[name="address"]').val(data.address);
                $('#editModal input[name="no_hp"]').val(data.no_hp);  // Pastikan field ini ada

                // Update action form untuk update data
                $('#formEdit').attr('action', `/peserta/${id}`);
                
                // Menampilkan modal
                $('#editModal').modal('show');
            });
        });

        $('#pesertaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("peserta.data") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ]
        });

        
    });
</script>
@endpush
