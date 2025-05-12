@extends('main')
@section('content')

<style>
    .modal-center {
        margin-right: 20%;
    }
</style>

@include('layouts.style')

<!-- Modaal konfirm -->
                <div class="modal fade modal-center" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="formModalLabel">Tambah Member</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form class="row g-3 needs-validation" novalidate="" action="{{ route('store.member') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="col-md-12">
                                <label for="fancy-file-upload" class="form-label">Image</label>
                                <div class="upload-card" id="upload-card">
                                    <div id="image-preview" style="display: none;"></div>
                                    <label for="fancy-file-upload" class="upload-label">Choose Image</label>
                                    <input id="fancy-file-upload" type="file" name="avatar" accept=".jpg, .png, image/jpeg, image/png" onchange="previewImage(event)">
                                    <button id="remove-button" class="remove-button" style="display: none;" type="button" onclick="removeImage()">&times;</button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation1" class="form-label">Name</label>
                                <input type="text" class="form-control" id="bsValidation1" placeholder="Input name ..." required name="name">
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation2" class="form-label">Member Id</label>
                                <input type="text" class="form-control" id="bsValidation2" placeholder="Member Id" name="member_id" required>
                                <div class="valid-feedback">Looks good!</div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation3" class="form-label">Email</label>
                                <input type="text" class="form-control" id="bsValidation3" placeholder="email" name="email" required>
                                <div class="invalid-feedback">Please choose a username.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation4" class="form-label">Password</label>
                                <input type="password" class="form-control" id="bsValidation4" placeholder="password" name="Password" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="bsValidation4" class="form-label">Phone</label>
                                <input type="number" class="form-control" id="bsValidation4" placeholder="number" name="phone" required>
                                <div class="invalid-feedback">Please provide a valid email.</div>
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

<div class="page-wrapper">
    <div class="page-content">


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
              <table class="table align-middle mb-0">
               <thead class="table-light">
                <tr>
                  <th>No</th>
                  <th>Name</th>
                  <th>Member_id</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Avatar</th>
                  <th colspan="2">Aksi</th>
                  
                </tr>
                </thead>
                <tbody>
                @foreach ($members as $key => $item)
                <tr>
                 <td>{{ $key +1 }}</td>
                 <td>{{ $item->name }}</td>
                 <td>{{ $item->member_id }}</td>
                 <td>{{ $item->email }}</td>
                 <td>{{ $item->phone }}</td>
                 <td>
                    <img src="{{ asset('storage/' . $item->avatar) }}" class="product-img-2" alt="product img">
                </td>
                 <td>
                    <a href="{{ $item->id }}" class="badge bg-primary" data-bs-toggle="modal" data-bs-target="#formModal">Konfirm</a>
                    <a href="{{ $item->id }}" class="badge bg-danger" data-bs-toggle="modal" data-bs-target="#formTolak">Tolak</a>
                </td>

                </tr>

                

                @endforeach

                

                
               </tbody>
             </table>
             </div>
            </div>
        </div>
        

            </div>
        </div>


@endsection