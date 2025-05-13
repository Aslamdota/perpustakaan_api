@extends('main')
@push('css')
<link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" rel="stylesheet" />
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --success-color: #4cc9f0;
        --danger-color: #f72585;
        --warning-color: #f8961e;
        --info-color: #4895ef;
        --light-color: #f8f9fa;
        --dark-color: #212529;
    }
    
    /* Badge Styles */
    .badge-pending { background-color: #6c757d; color: white; }
    .badge-borrowed { background-color: var(--primary-color); color: white; }
    .badge-returned { background-color: var(--success-color); color: white; }
    .badge-rejected { background-color: var(--danger-color); color: white; }
    .badge-overdue { background-color: var(--warning-color); color: white; }
    
    /* Card Styles */
    .card-return {
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        border: none;
        transition: transform 0.3s ease;
    }
    
    .card-return:hover {
        transform: translateY(-5px);
    }
    
    .card-header-return {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 15px 15px 0 0 !important;
        padding: 1.5rem;
    }
    
    /* Button Styles */
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
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
    }
    
    .btn-outline-return {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .btn-outline-return:hover {
        background-color: var(--primary-color);
        color: white;
    }
    
    /* Modal Styles */
    .modal-header-return {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-bottom: none;
    }
    
    /* Table Styles */
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
    
    .table-return tr:hover td {
        background-color: rgba(67, 97, 238, 0.05);
    }
    
    /* Status Badge */
    .status-badge {
        padding: 0.35em 0.65em;
        font-size: 0.75em;
        font-weight: 600;
        border-radius: 50rem;
        text-transform: capitalize;
    }
    
    /* Fine Amount */
    .fine-amount {
        font-weight: 600;
        color: var(--danger-color);
    }
    
    /* General Styles */
    .cursor-pointer { cursor: pointer; }
    .text-ellipsis { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    
    /* Tab Styles */
    .nav-tabs .nav-link {
        border: none;
        color: var(--dark-color);
        font-weight: 500;
        padding: 0.75rem 1.5rem;
    }
    
    .nav-tabs .nav-link.active {
        color: var(--primary-color);
        border-bottom: 3px solid var(--primary-color);
        background-color: transparent;
    }
    
    .nav-tabs .nav-link:hover:not(.active) {
        color: var(--primary-color);
    }
    
    /* Animation for buttons */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .btn-pulse:hover {
        animation: pulse 1.5s infinite;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: var(--primary-color);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: var(--secondary-color);
    }
    
    /* Loading Spinner */
    .spinner-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 200px;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1rem;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .card-header-return {
            padding: 1rem;
        }
        
        .nav-tabs .nav-link {
            padding: 0.5rem 1rem;
        }
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
                        <h6 class="mb-0 text-white">Manajemen Pengembalian Buku</h6>
                        <p class="mb-0 text-white-50">Kelola proses pengembalian dan riwayat peminjaman</p>
                    </div>
                    <div class="ms-auto">
                        <button class="btn btn-light btn-sm btn-pulse" data-bs-toggle="modal" data-bs-target="#fineSettingModal">
                            <i class="bx bx-cog me-1"></i> Pengaturan Denda
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="nav nav-tabs nav-primary mb-4" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" data-bs-toggle="tab" href="#pendingReturns" role="tab" aria-selected="true">
                            <i class="bx bx-time me-1"></i> Pending
                        </a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" data-bs-toggle="tab" href="#returnHistory" role="tab" aria-selected="false">
                            <i class="bx bx-history me-1"></i> Riwayat
                        </a>
                    </li>
                </ul>
                
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="pendingReturns" role="tabpanel">
                        <div class="table-responsive">
                            <table id="pending-table" class="table table-return table-hover" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Kode</th>
                                        <th>Buku</th>
                                        <th>Member</th>
                                        <th width="10%">Pinjam</th>
                                        <th width="10%">Jatuh Tempo</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="tab-pane fade" id="returnHistory" role="tabpanel">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                                    <input type="text" id="date-range-filter" class="form-control" placeholder="Filter tanggal">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select id="status-filter" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="returned">Dikembalikan</option>
                                    <option value="overdue">Terlambat</option>
                                    <option value="rejected">Ditolak</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="text" id="search-history" class="form-control" placeholder="Cari...">
                                    <button class="btn btn-outline-secondary" type="button" id="reset-filters">
                                        <i class="bx bx-reset"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="table-responsive">
                            <table id="history-table" class="table table-return table-hover" style="width:100%">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
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

<!-- Return Detail Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header modal-header-return">
                <h5 class="modal-title text-white">Detail Pengembalian</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary"><i class="bx bx-book me-2"></i> Informasi Buku</h6>
                                <hr>
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <img id="book-cover" src="" alt="Book Cover" class="rounded" width="80" height="120" onerror="this.src='{{ asset('assets/images/no-image.jpg') }}'">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 id="book-title" class="mb-1"></h5>
                                        <p class="mb-1"><small class="text-muted">Kode: </small><span id="book-code"></span></p>
                                        <p class="mb-1"><small class="text-muted">ISBN: </small><span id="book-isbn"></span></p>
                                        <p class="mb-1"><small class="text-muted">Pengarang: </small><span id="book-author"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-3 border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title text-primary"><i class="bx bx-user me-2"></i> Informasi Member</h6>
                                <hr>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0">
                                        <img id="member-photo" src="" alt="Member Photo" class="rounded-circle" width="60" height="60" onerror="this.src='{{ asset('assets/images/avatars/default.png') }}'">
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 id="member-name" class="mb-0"></h5>
                                        <p class="mb-0"><small class="text-muted">ID: </small><span id="member-id"></span></p>
                                        <p class="mb-0"><small class="text-muted">Email: </small><span id="member-email"></span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="card-title text-primary"><i class="bx bx-calendar me-2"></i> Detail Peminjaman</h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Tanggal Pinjam</label>
                                    <p id="detail-loan-date" class="fw-bold"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Jatuh Tempo</label>
                                    <p id="detail-due-date" class="fw-bold"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Tanggal Kembali</label>
                                    <p id="detail-return-date" class="fw-bold"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Status</label>
                                    <p><span id="detail-status" class="status-badge"></span></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Denda</label>
                                    <p id="detail-fine" class="fine-amount fw-bold"></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Lama Pinjam</label>
                                    <p id="detail-duration" class="fw-bold"></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted">Catatan</label>
                            <p id="detail-notes" class="fw-bold"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-return" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-return" id="print-receipt">
                    <i class="bx bx-printer me-1"></i> Cetak Bukti
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Gunakan CDN untuk moment.js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js"></script>

<!-- Gunakan CDN untuk daterangepicker -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    // Check if required elements exist before initializing
    if (!$('meta[name="csrf-token"]').length) {
        console.error('CSRF token meta tag is missing');
        return;
    }

    // Set CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize date range picker only if element exists
    if ($('#date-range-filter').length) {
        $('#date-range-filter').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD',
                applyLabel: 'Terapkan',
                cancelLabel: 'Batal',
                fromLabel: 'Dari',
                toLabel: 'Sampai',
                customRangeLabel: 'Custom',
                daysOfWeek: ['Mg', 'Sn', 'Sl', 'Rb', 'Km', 'Jm', 'Sb'],
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                firstDay: 1
            },
            opens: 'right',
            autoUpdateInput: false
        });

        $('#date-range-filter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            if (typeof historyTable !== 'undefined') {
                historyTable.draw();
            }
        });

        $('#date-range-filter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            if (typeof historyTable !== 'undefined') {
                historyTable.draw();
            }
        });
    }

    // Set today as default return date if element exists
    if ($('#return_date').length) {
        $('#return_date').val(new Date().toISOString().split('T')[0]);
    }
    
    // Initialize DataTables only if tables exist
    var pendingTable, historyTable;
    
    if ($('#pending-table').length) {
        pendingTable = $('#pending-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('returns.pending_data') }}",
                type: "GET"
            },
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada peminjaman yang perlu diproses",
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
                        return 'PJ-' + (data ? data.toString().padStart(4, '0') : '0000');
                    }
                },
                { 
                    data: 'book.title', 
                    name: 'book.title',
                    render: function(data, type, row) {
                        if (!row.book) return '-';
                        return `<div class="d-flex align-items-center cursor-pointer" onclick="showBookDetail(${row.book.id || 0})">
                            <div class="ms-2">
                                <h6 class="mb-0 text-ellipsis">${data || '-'}</h6>
                                <small class="text-muted">Kode: ${row.book.code || '-'}</small>
                            </div>
                        </div>`;
                    }
                },
                { 
                    data: 'member.name', 
                    name: 'member.name',
                    render: function(data, type, row) {
                        if (!row.member) return '-';
                        return `<div class="cursor-pointer" onclick="showMemberDetail(${row.member.id || 0})">
                            <h6 class="mb-0 text-ellipsis">${data || '-'}</h6>
                            <small class="text-muted">ID: ${row.member.member_id || '-'}</small>
                        </div>`;
                    }
                },
                { 
                    data: 'loan_date', 
                    name: 'loan_date',
                    render: function(data) {
                        return data ? moment(data).format('DD MMM YYYY') : '-';
                    }
                },
                { 
                    data: 'due_date', 
                    name: 'due_date',
                    render: function(data) {
                        if (!data) return '-';
                        const dueDate = moment(data);
                        const today = moment();
                        const isOverdue = dueDate.isBefore(today, 'day');
                        
                        return `<span class="fw-bold ${isOverdue ? 'text-danger' : ''}">${dueDate.format('DD MMM YYYY')}</span>`;
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
                            default: statusText = data || '-';
                        }
                        return `<span class="status-badge badge-${data || ''}">${statusText}</span>`;
                    }
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, row) {
                        if (!row.id) return '-';
                        return `
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-return process-return" data-id="${row.id}" title="Proses Pengembalian">
                                    <i class="bx bx-undo me-1"></i> Proses
                                </button>
                                <button class="btn btn-sm btn-outline-secondary view-detail" data-id="${row.id}" title="Lihat Detail">
                                    <i class="bx bx-show"></i>
                                </button>
                            </div>
                        `;
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
                
                $('.view-detail').hover(
                    function() {
                        $(this).addClass('shadow-sm');
                    },
                    function() {
                        $(this).removeClass('shadow-sm');
                    }
                );
            }
        });
    }

    if ($('#history-table').length) {
        historyTable = $('#history-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('returns.history_data') }}",
                type: "GET",
                data: function(d) {
                    d.date_range = $('#date-range-filter').val();
                    d.status = $('#status-filter').val();
                    d.search = $('#search-history').val();
                }
            },
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada riwayat pengembalian",
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
                        return 'PJ-' + (data ? data.toString().padStart(4, '0') : '0000');
                    }
                },
                { 
                    data: 'book.title', 
                    name: 'book.title',
                    render: function(data, type, row) {
                        if (!row.book) return '-';
                        return `<div class="d-flex align-items-center cursor-pointer" onclick="showBookDetail(${row.book.id || 0})">
                            <div class="ms-2">
                                <h6 class="mb-0 text-ellipsis">${data || '-'}</h6>
                                <small class="text-muted">Kode: ${row.book.code || '-'}</small>
                            </div>
                        </div>`;
                    }
                },
                { 
                    data: 'member.name', 
                    name: 'member.name',
                    render: function(data, type, row) {
                        if (!row.member) return '-';
                        return `<div class="cursor-pointer" onclick="showMemberDetail(${row.member.id || 0})">
                            <h6 class="mb-0 text-ellipsis">${data || '-'}</h6>
                            <small class="text-muted">ID: ${row.member.member_id || '-'}</small>
                        </div>`;
                    }
                },
                { 
                    data: 'loan_date', 
                    name: 'loan_date',
                    render: function(data) {
                        return data ? moment(data).format('DD MMM YYYY') : '-';
                    }
                },
                { 
                    data: 'due_date', 
                    name: 'due_date',
                    render: function(data) {
                        return data ? moment(data).format('DD MMM YYYY') : '-';
                    }
                },
                { 
                    data: 'return_date', 
                    name: 'return_date',
                    render: function(data) {
                        if (!data) return '-';
                        return moment(data).format('DD MMM YYYY');
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
                            default: statusText = data || '-';
                        }
                        return `<span class="status-badge badge-${data || ''}">${statusText}</span>`;
                    }
                }
            ],
            drawCallback: function(settings) {
                // Add hover effects to clickable elements
                $('.cursor-pointer').hover(
                    function() {
                        $(this).css('opacity', '0.8');
                    },
                    function() {
                        $(this).css('opacity', '1');
                    }
                );
            }
        });
    }

    // Filter events
    if ($('#status-filter').length) {
        $('#status-filter').change(function() {
            if (typeof historyTable !== 'undefined') {
                historyTable.draw();
            }
        });
    }

    if ($('#search-history').length) {
        $('#search-history').keyup(function() {
            if (typeof historyTable !== 'undefined') {
                historyTable.draw();
            }
        });
    }

    if ($('#reset-filters').length) {
        $('#reset-filters').click(function() {
            $('#date-range-filter').val('');
            $('#status-filter').val('');
            $('#search-history').val('');
            if (typeof historyTable !== 'undefined') {
                historyTable.draw();
            }
        });
    }

    // Load fine settings
    function loadFineSettings() {
        $.ajax({
            url: "{{ route('fine.get') }}",
            type: "GET",
            success: function(data) {
                if ($('#fine_amount').length) {
                    $('#fine_amount').val(data.fine_amount);
                }
                if ($('#grace_period').length) {
                    $('#grace_period').val(data.grace_period);
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat pengaturan denda',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    }

    // Show return modal
    $(document).on('click', '.process-return', function() {
        var loanId = $(this).data('id');
        
        $.ajax({
            url: "/loans/" + loanId,
            type: "GET",
            beforeSend: function() {
                // Show loading indicator
                $('#returnModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data peminjaman...</p>
                    </div>
                `);
            },
            success: function(data) {
                // Restore form content
                $('#returnModal .modal-body').html(`
                    <div class="row g-3">
                        <input type="hidden" id="loan_id" name="loan_id" value="${data.id || ''}">
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
                `);
                
                // Set form values
                $('#loan_id').val(data.id || '');
                $('#loan_date').val(data.loan_date ? moment(data.loan_date).format('DD MMMM YYYY') : '-');
                $('#due_date').val(data.due_date ? moment(data.due_date).format('DD MMMM YYYY') : '-');
                $('#return_date').val(new Date().toISOString().split('T')[0]);
                
                // Store original due date for calculation
                $('#due_date').data('original-date', data.due_date);
                
                // Calculate fine if overdue
                if (data.due_date) {
                    calculateFine(data.due_date);
                }
                
                $('#returnModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat data peminjaman',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Handle return date change
    $(document).on('change', '#return_date', function() {
        var dueDate = $('#due_date').data('original-date');
        if (dueDate) {
            calculateFine(dueDate);
        }
    });

    function calculateFine(dueDate) {
        var returnDate = $('#return_date').val();
        if (!returnDate) return;
        
        var returnDateObj = new Date(returnDate);
        var dueDateObj = new Date(dueDate);
        
        if (returnDateObj > dueDateObj) {
            var diffDays = Math.ceil((returnDateObj - dueDateObj) / (1000 * 60 * 60 * 24));
            $.ajax({
                url: "{{ route('fine.get') }}",
                type: "GET",
                success: function(data) {
                    var fine = diffDays * data.fine_amount;
                    $('#fine').val('Rp ' + fine.toLocaleString('id-ID'));
                },
                error: function() {
                    $('#fine').val('Rp 0');
                }
            });
        } else {
            $('#fine').val('Rp 0');
        }
    }

    // Submit return form
    $('#returnForm').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var submitBtn = form.find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Menyimpan...');
        
        $.ajax({
            url: "/loans/" + ($('#loan_id').val() || '') + "/return",
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message || 'Pengembalian berhasil diproses',
                    timer: 2000,
                    showConfirmButton: false
                });
                
                $('#returnModal').modal('hide');
                if (typeof pendingTable !== 'undefined') {
                    pendingTable.ajax.reload();
                }
                if (typeof historyTable !== 'undefined') {
                    historyTable.ajax.reload();
                }
            },
            error: function(xhr) {
                let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat memproses pengembalian';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: errorMessage,
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan');
            }
        });
    });

    // Load and handle fine settings
    loadFineSettings();
    
    if ($('#fineSettingForm').length) {
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
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: response.message || 'Pengaturan denda berhasil disimpan',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#fineSettingModal').modal('hide');
                },
                error: function(xhr) {
                    let errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Terjadi kesalahan saat menyimpan pengaturan';
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: errorMessage,
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Simpan Pengaturan');
                }
            });
        });
    }

    // Show detail modal
    $(document).on('click', '.view-detail', function() {
        var loanId = $(this).data('id');
        
        $.ajax({
            url: "/loans/" + loanId,
            type: "GET",
            beforeSend: function() {
                // Show loading indicator
                $('#detailModal .modal-body').html(`
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail peminjaman...</p>
                    </div>
                `);
            },
            success: function(data) {
                // Populate modal with data
                var bookCover = data.book && data.book.cover_url ? data.book.cover_url : '{{ asset('assets/images/no-image.jpg') }}';
                var memberPhoto = data.member && data.member.photo_url ? data.member.photo_url : '{{ asset('assets/images/avatars/default.png') }}';
                
                $('#book-cover').attr('src', bookCover).on('error', function() {
                    $(this).attr('src', '{{ asset('assets/images/no-image.jpg') }}');
                });
                
                $('#book-title').text(data.book && data.book.title ? data.book.title : '-');
                $('#book-code').text(data.book && data.book.code ? data.book.code : '-');
                $('#book-isbn').text(data.book && data.book.isbn ? data.book.isbn : '-');
                $('#book-author').text(data.book && data.book.author ? data.book.author : '-');
                
                $('#member-photo').attr('src', memberPhoto).on('error', function() {
                    $(this).attr('src', '{{ asset('assets/images/avatars/default.png') }}');
                });
                
                $('#member-name').text(data.member && data.member.name ? data.member.name : '-');
                $('#member-id').text(data.member && data.member.member_id ? data.member.member_id : '-');
                $('#member-email').text(data.member && data.member.email ? data.member.email : '-');
                
                $('#detail-loan-date').text(data.loan_date ? moment(data.loan_date).format('DD MMMM YYYY') : '-');
                $('#detail-due-date').text(data.due_date ? moment(data.due_date).format('DD MMMM YYYY') : '-');
                $('#detail-return-date').text(data.return_date ? moment(data.return_date).format('DD MMMM YYYY') : '-');
                
                let statusText = '';
                switch(data.status) {
                    case 'pending': statusText = 'Menunggu'; break;
                    case 'borrowed': statusText = 'Dipinjam'; break;
                    case 'returned': statusText = 'Dikembalikan'; break;
                    case 'rejected': statusText = 'Ditolak'; break;
                    case 'overdue': statusText = 'Terlambat'; break;
                    default: statusText = data.status || '-';
                }
                $('#detail-status').text(statusText).removeClass().addClass(`badge-${data.status || ''}`);
                
                $('#detail-fine').text(data.fine ? 'Rp ' + parseInt(data.fine).toLocaleString('id-ID') : 'Rp 0');
                $('#detail-notes').text(data.notes || '-');
                
                // Calculate duration
                if (data.loan_date) {
                    const loanDate = moment(data.loan_date);
                    const returnDate = data.return_date ? moment(data.return_date) : moment();
                    const duration = returnDate.diff(loanDate, 'days') + 1;
                    $('#detail-duration').text(duration + ' hari');
                } else {
                    $('#detail-duration').text('-');
                }
                
                $('#detailModal').modal('show');
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat detail peminjaman',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Print receipt
    if ($('#print-receipt').length) {
        $('#print-receipt').click(function() {
            Swal.fire({
                icon: 'info',
                title: 'Cetak Bukti',
                text: 'Fitur cetak bukti akan diimplementasikan',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
});

// Show book detail
function showBookDetail(bookId) {
    if (!bookId) return;
    
    Swal.fire({
        title: 'Memuat detail buku...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            $.ajax({
                url: "/books/" + bookId,
                type: "GET",
                success: function(data) {
                    Swal.fire({
                        title: data.title || 'Detail Buku',
                        html: `
                            <div class="text-start">
                                <p><strong>Kode:</strong> ${data.code || '-'}</p>
                                <p><strong>ISBN:</strong> ${data.isbn || '-'}</p>
                                <p><strong>Pengarang:</strong> ${data.author || '-'}</p>
                                <p><strong>Penerbit:</strong> ${data.publisher || '-'}</p>
                                <p><strong>Tahun:</strong> ${data.year || '-'}</p>
                                <p><strong>Kategori:</strong> ${data.category || '-'}</p>
                            </div>
                        `,
                        showConfirmButton: true
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat detail buku',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}

// Show member detail
function showMemberDetail(memberId) {
    if (!memberId) return;
    
    Swal.fire({
        title: 'Memuat detail member...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            
            $.ajax({
                url: "/members/" + memberId,
                type: "GET",
                success: function(data) {
                    Swal.fire({
                        title: data.name || 'Detail Member',
                        html: `
                            <div class="text-start">
                                ${data.avatar ? `<img src="${data.avatar}" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='{{ asset('assets/images/avatars/default.png') }}'">` : ''}
                                <p><strong>ID Member:</strong> ${data.member_id || '-'}</p>
                                <p><strong>Email:</strong> ${data.email || '-'}</p>
                                <p><strong>Telepon:</strong> ${data.phone || '-'}</p>
                                <p><strong>Alamat:</strong> ${data.address || '-'}</p>
                                <p><strong>Status:</strong> ${data.status || '-'}</p>
                            </div>
                        `,
                        showConfirmButton: true
                    });
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal memuat detail member',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}
</script>
@endpush