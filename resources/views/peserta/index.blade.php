@extends('layouts.adminlte')

@section('content')
<div class="container">
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<h3 class="mb-4">Data Peserta</h3>

<div class="d-flex justify-content-between mb-3">
    <button class="btn btn-kuning" data-toggle="modal" data-target="#createModal">Tambah Peserta</button>
    <div>
    <button id="startSlide" class="btn btn-kuning btn-sm" aria-label="Start">
        <i class="fas fa-play"></i>
    </button>
    <button id="stopSlide" class="btn btn-kuning btn-sm" aria-label="Stop">
        <i class="fas fa-stop"></i>
    </button>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <form action="{{ route('peserta.import') }}" method="POST" enctype="multipart/form-data" class="form-inline mb-0">
        @csrf
        <div class="form-group mr-2 mb-0">
            <label for="file" class="mr-2 mb-0">Upload Excel:</label>
            <input type="file" name="file" class="form-control" required accept=".xlsx, .xls">
        </div>
        <button type="submit" class="btn btn-kuning">Import</button>
    </form>

    <a href="{{ route('template.peserta.download') }}" class="btn btn-kuning">
        <i class="fas fa-download"></i> Download Template Excel
    </a>
</div>




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
    <tbody>
        <!-- Data will be populated here -->
    </tbody>
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

        var table = $('#pesertaTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("peserta.data") }}',
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'address', name: 'address' },
                { data: 'no_hp', name: 'no_hp' },
                { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
            ],
            responsive: true  // Mengaktifkan responsivitas
        });
        
        // Variabel untuk interval
        let intervalId = null;
        let currentPage = 0;

        // Fungsi untuk mulai slideshow
        function startSlide() {
            stopSlide(); // pastikan tidak double
            intervalId = setInterval(function () {
                let pageInfo = table.page.info();
                currentPage = (currentPage + 1) % pageInfo.pages;
                table.page(currentPage).draw('page');
            }, 10000); // 10 detik
        }

        // Fungsi untuk stop slideshow
        function stopSlide() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }
        startSlide();
        // Event listener untuk tombol
        $('#startSlide').on('click', function () {
            startSlide();
        });

        $('#stopSlide').on('click', function () {
            stopSlide();
        });
        
    });

    

    
</script>
@endpush
