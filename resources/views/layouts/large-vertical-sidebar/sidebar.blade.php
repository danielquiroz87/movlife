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
            <li class="nav-item {{ request()->is('customers/*') ? 'active' : '' }}" data-item="users">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="nav-text">Usuarios</span>
                </a>
                <div class="triangle"></div>
            </li>

            <li class="nav-item {{ request()->is('customers/*') ? 'active' : '' }}" data-item="operaciones">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Split-Horizontal-2-Window"></i>
                    <span class="nav-text">Operaciones</span>
                </a>
                <div class="triangle"></div>
            </li>
             <li class="nav-item {{ request()->is('customers/*') ? 'active' : '' }}" data-item="formularios">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-File-Clipboard-File--Text"></i>
                    <span class="nav-text">Formularios</span>
                </a>
                <div class="triangle"></div>
            </li>

            <li class="nav-item {{ request()->is('customers/*') ? 'active' : '' }}" data-item="informes">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-File-Clipboard-Text--Image"></i>
                    <span class="nav-text">Informes</span>
                </a>
                <div class="triangle"></div>
            </li>

             <li class="nav-item {{ request()->is('customers/*') ? 'active' : '' }}" data-item="users">
                <a class="nav-item-hold" href="#">
                    <i class="nav-icon i-Gear"></i>
                    <span class="nav-text">Configuración</span>
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
                    href="{{route('home')}}">
                    <i class="nav-icon i-Clock-3"></i>
                    <span class="item-name">Dashboard</span>
                </a>
            </li>
            
        </ul>

        <ul class="childNav" data-parent="users">
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='customers' ? 'open' : '' }}"
                    href="{{route('customers')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Clientes</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='employes' ? 'open' : '' }}"
                    href="{{route('employes')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Empleados</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='propietarios' ? 'open' : '' }}"
                    href="{{route('propietarios')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Propietarios</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='pasajeros' ? 'open' : '' }}"
                    href="{{route('pasajeros')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Pasajeros</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='conductores' ? 'open' : '' }}"
                    href="{{route('conductores')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Conductores</span>
                </a>
            </li>
            
        </ul>

       <ul class="childNav" data-parent="operaciones">
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='vehiculos' ? 'open' : '' }}"
                    href="{{route('vehiculos')}}">
                    <i class="nav-icon i-Car-2"></i>
                    <span class="item-name">Vehiculos</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='conductores' ? 'open' : '' }}"
                    href="{{route('conductores')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Conductores</span>
                </a>
            </li>

            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='servicios' ? 'open' : '' }}"
                    href="{{route('servicios')}}">
                    <i class="nav-icon i-Car-Wheel"></i>
                    <span class="item-name">Servicios</span>
                </a>
            </li>
       </ul> 

        <ul class="childNav" data-parent="formularios">
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='customers' ? 'open' : '' }}"
                    href="{{route('customers')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Clientes</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='employes' ? 'open' : '' }}"
                    href="{{route('employes')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Empleados</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='propietarios' ? 'open' : '' }}"
                    href="{{route('propietarios')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Propietarios</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='pasajeros' ? 'open' : '' }}"
                    href="{{route('pasajeros')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Pasajeros</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='conductores' ? 'open' : '' }}"
                    href="{{route('conductores')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Conductores</span>
                </a>
            </li>

             <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='servicios' ? 'open' : '' }}"
                    href="{{route('servicios')}}">
                    <i class="nav-icon i-Car-Wheel"></i>
                    <span class="item-name">Servicios</span>
                </a>
            </li>
            
        </ul>

        <ul class="childNav" data-parent="informes">
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='customers' ? 'open' : '' }}"
                    href="{{route('customers')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Clientes</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='employes' ? 'open' : '' }}"
                    href="{{route('employes')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Empleados</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='propietarios' ? 'open' : '' }}"
                    href="{{route('propietarios')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Propietarios</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='pasajeros' ? 'open' : '' }}"
                    href="{{route('pasajeros')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Pasajeros</span>
                </a>
            </li>
            <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='conductores' ? 'open' : '' }}"
                    href="{{route('conductores')}}">
                    <i class="nav-icon i-Add-User"></i>
                    <span class="item-name">Conductores</span>
                </a>
            </li>

             <li class="nav-item ">
                <a class="{{ Route::currentRouteName()=='servicios' ? 'open' : '' }}"
                    href="{{route('servicios')}}">
                    <i class="nav-icon i-Car-Wheel"></i>
                    <span class="item-name">Servicios</span>
                </a>
            </li>
            
        </ul>
      
    </div>
    <div class="sidebar-overlay"></div>
</div>
<!--=============== Left side End ================-->