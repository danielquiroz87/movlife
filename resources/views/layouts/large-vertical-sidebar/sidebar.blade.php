<div class="side-content-wrap">
    <div class="sidebar-left open rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <ul class="navigation-left">
            <li class="nav-item {{ request()->is('dashboard/*') ? 'active' : '' }}" data-item="dashboard">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Bar-Chart"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
                <div class="triangle"></div>
            </li>
           
        </ul>
    </div>

    <div class="sidebar-left-secondary rtl-ps-none" data-perfect-scrollbar data-suppress-scroll-x="true">
        <!-- Submenu Dashboards -->
        <ul class="childNav" data-parent="dashboard">
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='dashboard_version_1' ? 'open' : '' }}"
                    href="#">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Dashboard</span>
                </a>
            </li>
            
        </ul>

        </ul>
      
    </div>
    <div class="sidebar-overlay"></div>
</div>
<!--=============== Left side End ================-->