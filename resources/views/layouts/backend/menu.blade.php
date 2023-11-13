<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">admin monitoring <sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ Nav::isRoute('home') }}">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Dashboard') }}</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'petani')
        <div class="sidebar-heading">
            {{ __('Data Lahan') }}
        </div>
        <!-- Nav Item - About -->
        <li class="nav-item {{ Nav::isRoute('data_lahan') }}">
            <a class="nav-link" href="{{ route('data_lahan') }}">
                <i class="fas fa-fw fa-book"></i>
                <span>{{ __('Data Lahan') }}</span>
            </a>
        </li>
    @endif

    @if (Auth::user()->role == 'admin')
        <hr class="sidebar-divider">
        <div class="sidebar-heading">
            {{ __('Pengguna') }}
        </div>
        <!-- Nav Item - About -->
        <li class="nav-item {{ Nav::isRoute('admin') }}">
            <a class="nav-link" href="{{ route('admin') }}">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>{{ __('Data Admin') }}</span>
            </a>
        </li>
        <li class="nav-item {{ Nav::isRoute('petani') }}">
            <a class="nav-link" href="{{ route('petani') }}">
                <i class="fas fa-fw fa-hands-helping"></i>
                <span>{{ __('Data Petani') }}</span>
            </a>
        </li>
    @endif
    <!-- Heading -->
    <div class="sidebar-heading">
        {{ __('Settings') }}
    </div>

    <!-- Nav Item - Profile -->
    <li class="nav-item {{ Nav::isRoute('profile') }}">
        <a class="nav-link" href="{{ route('profile') }}">
            <i class="fas fa-fw fa-user"></i>
            <span>{{ __('Profile') }}</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
