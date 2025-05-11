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
                <tbody><tr>
                    @foreach($loans as $loan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $loan->book->title }}</td>
                            <td>{{ $loan->member->name }}</td>
                            <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                            <td>{{ $loan->status }}</td>
                            <td>
                                @if($loan->status == 'Pending')
                                    <form action="{{ route('loans.update', $loan->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button name="status" value="Approved" class="btn btn-success">Terima</button>
                                        <button name="status" value="Rejected" class="btn btn-danger">Tolak</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tr>
                </tbody>
                </tr>
               </tbody>
             </table>
             </div>
            </div>
        </div>


            </div>
        </div>


@endsection
