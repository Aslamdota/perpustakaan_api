<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">Admin Template</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
     </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            <ul>
                <li> <a href="index-2.html"><i class='bx bx-radio-circle'></i>Default</a>
                </li>
            </ul>
        </li>
       
        <li class="menu-label">Pages</li>
        <li>
            <a href="{{ route('view.peminjaman') }}">
                <div class="parent-icon"><i class='bx bx-bookmark-alt'></i>
                </div>
                <div class="menu-title">Peminjaman</div>
            </a>
        </li>
        <li>
            <a href="{{ url('returns') }}">
                <div class="parent-icon"><i class='bx bx-bookmark-alt'></i>
                </div>
                <div class="menu-title">Pengembalian</div>
            </a>
        </li>
        

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-book'></i>
                </div>
                <div class="menu-title">Buku</div>
            </a>
            <ul>
                <li> <a href="{{ route('view.books') }}"><i class='bx bx-table'></i>Tabel Buku</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class='bx bx-edit'></i>Edit Buku</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user'></i>
                </div>
                <div class="menu-title">Member</div>
            </a>
            <ul>
                <li> <a href="{{ route('view.member') }}"><i class='bx bx-table'></i>Tabel Member</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class='bx bx-edit'></i>Edit Member</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-user-circle'></i>
                </div>
                <div class="menu-title">User</div>
            </a>
            <ul>
                <li> <a href="{{ route('view.user') }}"><i class='bx bx-table'></i>Tabel User</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class='bx bx-edit'></i>Edit User</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-category'></i>
                </div>
                <div class="menu-title">Category</div>
            </a>
            <ul>
                <li> <a href="{{ route('view.category') }}"><i class='bx bx-table'></i>Tabel Category</a>
                </li>
                <li> <a href="ecommerce-products-details.html"><i class='bx bx-edit'></i>Edit Category</a>
                </li>
            </ul>
        </li>
    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->