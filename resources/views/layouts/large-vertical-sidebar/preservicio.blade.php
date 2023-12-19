<!-- ============ Large SIdebar Layout start ============= -->

<div class="app-admin-wrap layout-sidebar-large clearfix">
    <div class="main-content-wrap sidenav-open d-flex flex-column flex-grow-1" style="float: left;">

        <div class="main-content">
            @yield('main-content')
        </div>

        <div class="flex-grow-1"></div>
        @include('layouts.common.footer')
    </div>
    <!-- ============ Body content End ============= -->
</div>
