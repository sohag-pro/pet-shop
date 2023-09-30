<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Pet Shop</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{url('/')}}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Challenges
    </div>

    <li @if(last(request()->segments()) == 'orders-summery') class="nav-item active" @else class="nav-item" @endif >
        <a class="nav-link" href="{{url('/orders-summery')}}">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Order Summery</span>
        </a>
    </li>
    <li @if(last(request()->segments()) == 'weekly-orders-summery') class="nav-item active" @else class="nav-item" @endif>
        <a class="nav-link" href="{{url('/weekly-orders-summery')}}">
            <i class="fas fa-fw fa-calendar"></i>
            <span>Weekly Order Summery</span>
        </a>
    </li>
    <li @if(last(request()->segments()) == 'orders') class="nav-item active" @else class="nav-item" @endif>
        <a class="nav-link" href="{{url('/orders')}}">
            <i class="fas fa-fw fa-file"></i>
            <span>Orders</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Submission
    </div>

    <li class="nav-item">
        <a class="nav-link" target="_blank" href="https://github.com/sohag-pro/pet-shop#readme">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Github</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
