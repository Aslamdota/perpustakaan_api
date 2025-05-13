@extends('main')
@push('css')
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --warning-color: #f8961e;
        --info-color: #4895ef;
    }
    
    .badge-pending { background-color: #6c757d; color: white; }
    .badge-borrowed { background-color: var(--primary-color); color: white; }
    .badge-returned { background-color: var(--success-color); color: white; }
    .badge-rejected { background-color: var(--danger-color); color: white; }
    .badge-overdue { background-color: var(--warning-color); color: white; }
    
    .card-return {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: none;
    }
    
    .card-header-return {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }
    
    .btn-return {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-return:hover {
        background-color: var(--secondary-color);
        border-color: var(--secondary-color);
        transform: translateY(-2px);
    }
    
    .btn-outline-return {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-return:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    .modal-header-return {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
    }
    
    .table-return th {
        background-color: #f8f9fa;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    
    .table-return td {
        vertical-align: middle;
    }
    
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 50rem;
        text-transform: capitalize;
    }
    
    .fine-amount {
        font-weight: 600;
        color: var(--danger-color);
    }
    
    .cursor-pointer { cursor: pointer; }
    
    /* Animation for buttons */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .btn-pulse:hover {
        animation: pulse 1.5s infinite;
    }
</style>
@endpush

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <div class="card card-return radius-10">
            <div class="card-header card-header-return">
                <div class="d-flex align-items-center">
                    <div>
                        <h6 class="mb-0 text-white">Pengembalian Buku</h6>
                        <p class="mb-0 text-white-50">Manajemen pengembalian buku perpustakaan</p>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-light btn-sm btn-pulse" data-bs-toggle="modal" data-bs-target="#fineSettingModal">
                            <i class="bx bx-cog me-1"></i> Pengaturan Denda
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="returns-table" class="table table-return table-hover" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="10%">Kode</th>
                                <th>Buku</th>
                                <th>Member</th>
                                <th width="10%">Pinjam</th>
                                <th width="10%">Jatuh Tempo</th>
                                <th width="10%">Kembali</th>
                                <th width="10%">Denda</th>
                                <th width="10%">Status</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Return Modal -->
<div class="modal fade" id="returnModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-return">
                <h5 class="modal-title text-white">Proses Pengembalian Buku</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="returnForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="loan_date" placeholder="Tanggal Pinjam" readonly>
                                <label for="loan_date">Tanggal Pinjam</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="due_date" placeholder="Jatuh Tempo" readonly>
                                <label for="due_date">Jatuh Tempo</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="date" class="form-control" id="return_date" name="return_date" placeholder="Tanggal Kembali" required>
                                <label for="return_date">Tanggal Kembali</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control fine-amount" id="fine" name="fine" placeholder="Denda" readonly>
                                <label for="fine">Denda (Rp)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control" id="notes" name="notes" placeholder="Catatan" style="height: 100px"></textarea>
                                <label for="notes">Catatan Pengembalian</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="loan_id" name="loan_id">
                    <button type="button" class="btn btn-outline-return" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-return">
                        <i class="bx bx-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Fine Setting Modal -->
<div class="modal fade" id="fineSettingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-return">
                <h5 class="modal-title text-white">Pengaturan Denda</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="fineSettingForm">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="fine_amount" name="fine_amount" placeholder="Jumlah Denda" required>
                                <label for="fine_amount">Jumlah Denda per Hari (Rp)</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="number" class="form-control" id="grace_period" name="grace_period" placeholder="Masa Tenggang" required>
                                <label for="grace_period">Masa Tenggang (hari)</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-return" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-return">
                        <i class="bx bx-save me-1"></i> Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Set today as default return date
    $('#return_date').val(new Date().toISOString().split('T')[0]);
    
    // Initialize DataTable with enhanced options
    var table = $('#returns-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('returns.data') }}",
            type: "GET"
        },
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            zeroRecords: "Tidak ada data yang ditemukan",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'id', 
                name: 'id',
                className: 'text-center',
                render: function(data) {
                    return 'PJ-' + data.toString().padStart(4, '0');
                }
            },
            { 
                data: 'book.title', 
                name: 'book.title',
                render: function(data, type, row) {
                    return `<div class="d-flex align-items-center">
                        <div class="ms-2">
                            <h6 class="mb-0">${data}</h6>
                            <small class="text-muted">Kode: ${row.book.code || '-'}</small>
                        </div>
                    </div>`;
                }
            },
            { 
                data: 'member.name', 
                name: 'member.name',
                render: function(data, type, row) {
                    return `<div>
                        <h6 class="mb-0">${data}</h6>
                        <small class="text-muted">ID: ${row.member.member_id || '-'}</small>
                    </div>`;
                }
            },
            { 
                data: 'loan_date', 
                name: 'loan_date',
                render: function(data) {
                    return new Date(data).toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric' 
                    });
                }
            },
            { 
                data: 'due_date', 
                name: 'due_date',
                render: function(data) {
                    const formattedDate = new Date(data).toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric' 
                    });
                    return `<span class="fw-bold">${formattedDate}</span>`;
                }
            },
            { 
                data: 'return_date', 
                name: 'return_date',
                render: function(data) {
                    if (!data) return '-';
                    
                    const formattedDate = new Date(data).toLocaleDateString('id-ID', { 
                        day: 'numeric', 
                        month: 'long', 
                        year: 'numeric' 
                    });
                    
                    const statusClass = data ? 'text-success' : '';
                    return `<span class="fw-semibold ${statusClass}">${formattedDate}</span>`;
                }
            },
            { 
                data: 'fine', 
                name: 'fine',
                className: 'text-end',
                render: function(data) {
                    return data ? `<span class="fine-amount">Rp ${parseInt(data).toLocaleString('id-ID')}</span>` : '-';
                }
            },
            { 
                data: 'status', 
                name: 'status',
                className: 'text-center',
                render: function(data) {
                    let statusText = '';
                    switch(data) {
                        case 'pending': statusText = 'Menunggu'; break;
                        case 'borrowed': statusText = 'Dipinjam'; break;
                        case 'returned': statusText = 'Dikembalikan'; break;
                        case 'rejected': statusText = 'Ditolak'; break;
                        case 'overdue': statusText = 'Terlambat'; break;
                        default: statusText = data;
                    }
                    return `<span class="status-badge badge-${data}">${statusText}</span>`;
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center',
                render: function(data, type, row) {
                    if (row.status === 'returned' || row.status === 'rejected') {
                        return `<button class="btn btn-sm btn-outline-secondary" disabled>
                            <i class="bx bx-check-circle me-1"></i> Selesai
                        </button>`;
                    } else {
                        return `<button class="btn btn-sm btn-return process-return" data-id="${row.id}">
                            <i class="bx bx-undo me-1"></i> Proses
                        </button>`;
                    }
                }
            }
        ],
        drawCallback: function(settings) {
            // Add hover effects
            $('.process-return').hover(
                function() {
                    $(this).addClass('shadow-sm');
                },
                function() {
                    $(this).removeClass('shadow-sm');
                }
            );
        }
    });

    // Load fine settings
    function loadFineSettings() {
        $.ajax({
            url: "{{ route('fine.get') }}",
            type: "GET",
            success: function(data) {
                $('#fine_amount').val(data.fine_amount);
                $('#grace_period').val(data.grace_period);
            },
            error: function(xhr) {
                toastr.error('Gagal memuat pengaturan denda');

            }
        });
    }

    // Show return modal
    $(document).on('click', '.process-return', function() {
        var loanId = $(this).data('id');
        
        $.ajax({
            url: "/loans/" + loanId,
            type: "GET",
            success: function(data) {
                $('#loan_id').val(data.id);
                $('#loan_date').val(new Date(data.loan_date).toLocaleDateString('id-ID'));
                $('#due_date').val(new Date(data.due_date).toLocaleDateString('id-ID'));
                
                // Calculate fine if overdue
                var returnDate = new Date($('#return_date').val());
                var dueDate = new Date(data.due_date);
                
                if (returnDate > dueDate) {
                    var diffDays = Math.ceil((returnDate - dueDate) / (1000 * 60 * 60 * 24));
                    $.ajax({
                        url: "{{ route('fine.get') }}",
                        type: "GET",
                        success: function(fineData) {
                            var fine = diffDays * fineData.fine_amount;
                            $('#fine').val('Rp ' + fine.toLocaleString('id-ID'));
                        }
                    });
                } else {
                    $('#fine').val('Rp 0');
                }
                
                $('#returnModal').modal('show');
            },
            error: function(xhr) {
                toastr.error('Gagal memuat data peminjaman');
            }
        });
    });

    // Handle return date change
    $('#return_date').change(function() {
        var dueDate = new Date($('#due_date').val());
        var returnDate = new Date($(this).val());
        
        if (returnDate > dueDate) {
            var diffDays = Math.ceil((returnDate - dueDate) / (1000 * 60 * 60 * 24));
            $.ajax({
                url: "{{ route('fine.get') }}",
                type: "GET",
                success: function(data) {
                    var fine = diffDays * data.fine_amount;
                    $('#fine').val('Rp ' + fine.toLocaleString('id-ID'));
                }
            });
        } else {
            $('#fine').val('Rp 0');
        }
    });

    // Submit return form
    $('#returnForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');
        
        $.ajax({
            url: "/loans/" + $('#loan_id').val() + "/return",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
                $('#returnModal').modal('hide');
                table.ajax.reload();
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan saat memproses pengembalian';
                toastr.error(response.errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
            }
        });
    });

    // Load and handle fine settings
    loadFineSettings();
    
    $('#fineSettingForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');
        
        $.ajax({
            url: "{{ route('fine.update') }}",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                toastr.success(response.message);
                $('#fineSettingModal').modal('hide');
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON.message || 'Terjadi kesalahan saat menyimpan pengaturan';
                toastr.error(response.errorMessage);
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Pengaturan');
            }
        });
    });

    // Initialize datepicker and fine calculation
    $('#return_date').val(new Date().toISOString().split('T')[0]);
});
</script>
@endpush