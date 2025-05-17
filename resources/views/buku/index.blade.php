@extends('main')

@push('css')
<link href="{{ asset('assets/custom/css/sweetalert2.min.css') }}" rel="stylesheet" />
{{-- data table --}}
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
{{-- <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" /> --}}

@endpush
@section('content')

<style>
    .modal-center {
        margin-right: 20%;
    }
</style>

@include('layouts.style')

<div class="page-wrapper">
    <div class="page-content">

        <!-- Modal -->
        <div class="modal fade modal-center" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formModalLabel">Tambah Data</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" novalidate="" action="{{ route('store.books') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="fancy-file-upload" class="form-label">Image</label>
                                <div class="upload-card" id="upload-card">
                                    <div id="image-preview" style="display: none;"></div>
                                    <label for="fancy-file-upload" class="upload-label">Choose Image</label>
                                    <input id="fancy-file-upload" type="file" name="cover_image" accept=".jpg, .png, image/jpeg, image/png" onchange="previewImage(event)">
                                    <button id="remove-button" class="remove-button" style="display: none;" type="button" onclick="removeImage()">&times;</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation1" class="form-label">Title</label>
                                <input type="text" class="form-control @error('title')
                                    is-invalid
                                @enderror" id="bsValidation1" placeholder="Title" required name="title">
                                @error('title')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation2" class="form-label">Author</label>
                                <input type="text" class="form-control @error('author')
                                    is-invalid
                                @enderror" id="bsValidation2" placeholder="Author Books" name="author" required>
                                @error('author')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation3" class="form-label">Publisher</label>
                                <input type="text" class="form-control @error('publisher')
                                    is-invalid
                                @enderror" id="bsValidation3" placeholder="publisher" name="publisher" required>
                                @error('publisher')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation4" class="form-label">Isbn</label>
                                <input type="text" class="form-control @error('isbn')
                                    is-invalid
                                @enderror" id="bsValidation4" placeholder="isbn" name="isbn" required>
                                @error('isbn')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control @error('publication_year')
                                    is-invalid
                                @enderror" id="year" placeholder="Year" name="publication_year" required>
                               @error('publication_year')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" class="form-control @error('stock')
                                    is-invalid
                                @enderror" id="stock" placeholder="stock" name="stock" required>
                                @error('stock')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation9" class="form-label">Kategori</label>
                                <select id="bsValidation9" name="category_id" class="form-select @error('category_id')
                                    is-invalid
                                @enderror" required>
                                    <option selected disabled value="">Pilih Kategory</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation13" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('description')
                                    is-invalid
                                @enderror" id="bsValidation13" placeholder="Deskripsi ..." name="description" rows="3" required></textarea>
                                @error('description')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <div class="d-md-flex d-grid align-items-center gap-3">
                                    <button type="submit" class="btn btn-primary px-4">Submit</button>
                                    <button type="reset" class="btn btn-light px-4">Reset</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>


        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal">Tambah</button>
                        </h6>
                    </div>
                    <div class="dropdown ms-auto">
                        <a class="dropdown-toggle dropdown-toggle-nocaret" href="#" data-bs-toggle="dropdown"><i class="bx bx-dots-horizontal-rounded font-22 text-option"></i>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="javascript:;">Action</a>
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Another action</a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="javascript:;">Something else here</a>
                            </li>
                        </ul>
                    </div>
                </div>
               </div>
            <div class="card-body">
            <div class="table-responsive">
              <table id="books-table" class="table align-middle mb-0">
               <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Title</th>
                  <th>Author</th>
                  <th>Publisher</th>
                  <th>Isbn</th>
                  <th>Year</th>
                  <th>Stok</th>
                  <th>Deskripsi</th>
                  <th>Kategori</th>
                  <th>Image</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>

               </tbody>
             </table>
             </div>
            </div>
        </div>
        

     </div>
</div>


@push('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
	
<script>
    $(document).ready(function() {
        $('#books-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route("view.books") }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'author', name: 'author' },
                { data: 'publisher', name: 'publisher' },
                { 
                    data: 'isbn', name: 'isbn',
                    render: function(data) {
                        return '<span class="badge bg-gradient-quepal text-white shadow-sm w-10">'+data+'</span>';
                    } 
                },
                { data: 'publication_year', name: 'publication_year' },
                { 
                    data: 'stock', 
                    name: 'stock',
                    render: function(data) {
                        return '<span class="badge bg-gradient-bloody text-white shadow-sm w-10">'+data+'</span>';
                    } 
                },
                { data: 'description', name: 'description' },
                { 
                    data: 'category', 
                    name: 'category', orderable: false, searchable: false,
                    render: function(data) {
                        return '<span class="badge bg-gradient-blooker text-white shadow-sm w-10">'+data+'</span>';
                    } 
                },
                { data: 'cover_image', name: 'cover_image', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ]
        });

        // Handle delete button
        $('#books-table').on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#books-table').DataTable().ajax.reload();
                            Swal.fire('Berhasil!', response.message, 'success');
                        },
                        error: function(xhr) {
                            Swal.fire('Gagal!', 'Gagal menghapus user.', 'error');
                        }
                    });
                }
            });
        });
    });
</script>

@endpush


@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myModal = new bootstrap.Modal(document.getElementById('formModal'));
        myModal.show();
    });
</script>
@endif


@endsection
