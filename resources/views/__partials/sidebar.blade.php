<div class="sidebar__wrapper">
    <div class="sidebar__header">
        <div class="sidebar__logo">
            <img src="{{ asset('assets/images/pixel.png') }}" alt="Logo Pixel">
        </div>
    </div>
    <div class="sidebar__body">
        <div class="sidebar__menu-wrapper">
            @can('dashboard')
            <a class="sidebar__menu-list" href="{{ url('/') }}">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/dashboard-icon.png') }}" alt="Dashboard Icon">
                    </div>
                    <div class="sidebar__menu-name {{ request()->is('/') ? 'active' : '' }}">
                        <span>
                            Dashboard
                        </span>
                    </div>
                </div>
            </a>
            @endcan
            @canany(['product_list','category_product_list'])
            <div class="sidebar__menu-list dropdown__toggle" href="javascript:void(0)">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/product-icon.png') }}" alt="Product Icon">
                    </div>
                    <div
                        class="sidebar__menu-name {{ request()->is('product') || request()->is('category') ? 'active' : '' }}">
                        <span>
                            Produk
                        </span>
                    </div>
                </div>
                <div class="sidebar__dropdown-menu-wrapper {{ request()->is('product') || request()->is('category') ? 'show' : '' }}">
                    @can('category_product_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/category') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('category') ? 'active' : '' }}">
                            <span>Kategori</span>
                        </div>
                    </a>
                    @endcan
                    @can('product_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/color') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('color') ? 'active' : '' }}">
                            <span>List Warna</span>
                        </div>
                    </a>
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/product') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('product') ? 'active' : '' }}">
                            <span>List Produk</span>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany
            @can('sales_list')
            <div class="sidebar__menu-list dropdown__toggle" href="javascript:void(0)">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/sale-icon.png') }}" alt="Sale Icon">
                       
                    </div>
                    <div
                        class="sidebar__menu-name {{ request()->is('sale') || request()->is('job') ? 'active' : '' }}">
                        <span>
                            Penjualan
                        </span>
                    </div>
                </div>
                <div class="sidebar__dropdown-menu-wrapper {{ request()->is('sale') || request()->is('job') ? 'show' : '' }}">
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/sale') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('sale') ? 'active' : '' }}">
                            <span>Penjualan</span>
                        </div>
                    </a>
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/job') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('job') ? 'active' : '' }}">
                            <span>
                                Pekerjaan Pelanggan
                            </span>
                        </div>
                    </a>        
                </div>
            </div>
            @endcan
            @canany(['employee_list','attendance_list','target_list','target_list'])
            <div class="sidebar__menu-list dropdown__toggle" href="javascript:void(0)">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/employee-icon.png') }}" alt="Employee Icon">
                    </div>
                    <div
                        class="sidebar__menu-name {{ request()->is('user') || request()->is('attendance') || request()->is('target') ? 'active' : '' }}">
                        <span>
                            Karyawan
                        </span>
                    </div>
                </div>
                <div
                    class="sidebar__dropdown-menu-wrapper {{ request()->is('user') || request()->is('attendance') || request()->is('target') ? 'show' : '' }}">
                    @can('employee_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/user') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('user') ? 'active' : '' }}">
                            <span>List Karyawan</span>
                        </div>
                    </a>
                    @endcan
                    @can('attendance_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/attendance') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('attendance') ? 'active' : '' }}">
                            <span>Jam Hadir</span>
                        </div>
                    </a>
                    @endcan
                    @can('target_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/target') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('target') ? 'active' : '' }}">
                            <span>Target</span>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany
            @can('region_list')
            <a class="sidebar__menu-list" href="{{ url('/region') }}">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/regional-icon.png') }}" alt="Region Icon">
                    </div>
                    <div class="sidebar__menu-name {{ request()->is('region') ? 'active' : '' }}">
                        <span>
                            Daerah
                        </span>
                    </div>
                </div>
            </a>
            @endcan
            @canany(['main_shop_list','branch_shop_list'])
            <div class="sidebar__menu-list dropdown__toggle" href="javascript:void(0)">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/store-icon.png') }}" alt="Store Icon">
                    </div>
                    <div
                        class="sidebar__menu-name {{ request()->is('main-store') || request()->is('branch-store') ? 'active' : '' }}">
                        <span>
                            Toko
                        </span>
                    </div>
                </div>
                <div
                    class="sidebar__dropdown-menu-wrapper {{ request()->is('main-store') || request()->is('branch-store') ? 'show' : '' }}">
                    @can('main_shop_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/main-store') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('main-store') ? 'active' : '' }}">
                            <span>Toko Utama</span>
                        </div>
                    </a>
                    @endcan
                    @can('branch_shop_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/branch-store') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('branch-store') ? 'active' : '' }}">
                            <span>Toko Cabang</span>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany
            @can('role_permission_list')
            <a class="sidebar__menu-list" href="{{ url('/role-permission') }}">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/role-icon.png') }}" alt="Role & Permission Icon">
                    </div>
                    <div class="sidebar__menu-name {{ request()->is('role-permission') ? 'active' : '' }}">
                        <span>
                            Peran & Hak Akses
                        </span>
                    </div>
                </div>
            </a>
            @endcan
            @canany(['sales_report_list','buyer_report_list','rush_hour_report_list','target_report_list'])
            <div class="sidebar__menu-list dropdown__toggle" href="javascript:void(0)">
                <div class="sidebar__menu">
                    <div class="sidebar__menu-icon">
                        <img src="{{ asset('assets/images/employee-icon.png') }}" alt="Report Icon">
                    </div>
                    <div
                        class="sidebar__menu-name {{ request()->is('sale-report') ||request()->is('customer-report') ||request()->is('busy-report') ||request()->is('target-report')? 'active': '' }}">
                        <span>
                            Laporan
                        </span>
                    </div>
                </div>
                <div
                    class="sidebar__dropdown-menu-wrapper {{ request()->is('sale-report') ||request()->is('customer-report') ||request()->is('busy-report') ||request()->is('target-report')? 'show': '' }}">
                    @can('sales_report_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/sale-report') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('sale-report') ? 'active' : '' }}">
                            <span>Penjualan</span>
                        </div>
                    </a>
                    @endcan
                    @can('buyer_report_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/customer-report') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div
                        class="sidebar__dropdown-menu-name {{ request()->is('customer-report') ? 'active' : '' }}">
                        <span>Pembeli</span>
                        </div>
                    </a>
                    @endcan
                    @can('rush_hour_report_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/busy-report') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div class="sidebar__dropdown-menu-name {{ request()->is('busy-report') ? 'active' : '' }}">
                            <span>Keramaian</span>
                        </div>
                    </a>
                    @endcan
                    @can('target_report_list')
                    <a class="sidebar__dropdown-menu-list" href="{{ url('/target-report') }}">
                        <div>
                            <img src="{{ asset('assets/images/sidebar-dot-icon.png') }}" alt="Dot Icon">
                        </div>
                        <div
                        class="sidebar__dropdown-menu-name {{ request()->is('target-report') ? 'active' : '' }}">
                        <span>Target</span>
                        </div>
                    </a>
                    @endcan
                </div>
            </div>
            @endcanany
        </div>
    </div>
</div>
