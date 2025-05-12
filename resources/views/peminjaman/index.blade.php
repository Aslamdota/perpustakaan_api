@extends('main')
@section('content')

<style>
    .modal-center {
        margin-right: 20%;
    }
</style>

<div class="page-wrapper">
    <div class="page-content">


        <div class="card radius-10">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center">
                    
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
              <table class="table align-middle mb-0">
               <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Buku</th>
                  <th>Member</th>
                  <th>Tanggal Peminjaman</th>
                  <th>Status</th>
                  <th>Aksi</th>
                  
                </tr>
                </thead>
                <tbody>
                @foreach ($borrowings as $key => $item)
                <tr>
                 <td>{{ $key +1 }}</td>
                 <td>{{ $item->book->title }}</td>
                 <td>{{ $item->member->name }}</td>
                 <td>{{ date('d F Y', strtotime($item->borrow_date)) }}</td>
                 <td><span class="badge bg-gradient-quepal text-white shadow-sm w-10">{{ $item->status }}</span></td>
                 <td>
                    <a href="{{ $item->id }}" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#formModal">Konfirm</a>
                    <a href="{{ $item->id }}" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#formTolak">Tolak</a>
                </td>

                </tr>

                <!-- Modaal konfirm -->
                <div class="modal fade modal-center" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="formModalLabel">Konfirmasi Peminjaman</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('borrowings.confirm', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="col-md-6">
                                        <label for="due_date" class="form-label">Tanggal Dikembalikan</label>
                                        <input type="date" class="form-control" id="due_date" name="due_date" required>
                                        <div class="invalid-feedback">Masukkan tanggal pengembalian.</div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="noted" class="form-label">Catatan</label>
                                        <textarea class="form-control" id="noted" name="noted" placeholder="Catatan ..." rows="3"></textarea>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                                        <button type="reset" class="btn btn-light px-4">Reset</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modaal tolak -->
                <div class="modal fade modal-center" id="formTolak" tabindex="-1" aria-labelledby="formTolakLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="formModalLabel">Catatan Penolakan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('borrowings.reject', $item->id) }}" method="POST">
                                    @csrf
                                    <div class="col-md-12">
                                        <label for="noted" class="form-label">Alasan Penolakan</label>
                                        <textarea class="form-control" id="noted" name="noted" placeholder="Masukkan alasan penolakan ..." rows="3" required></textarea>
                                        <div class="invalid-feedback">Masukkan alasan penolakan.</div>
                                    </div>

                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary px-4">Submit</button>
                                        <button type="reset" class="btn btn-light px-4">Reset</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                

                
               </tbody>
             </table>
             </div>
            </div>
        </div>
        

            </div>
        </div>


@endsection