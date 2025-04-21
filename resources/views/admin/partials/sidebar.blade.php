<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="index.html" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('assets/img/admin/logo-black.png') }}" alt="Logo" width="200">
            </span>
            <span class="app-brand-text demo menu-text fw-bold ms-2"></span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <!-- Dashboards -->
        <li class="menu-item {{ request()->is('/') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Dashboards">Dashboards</div>
                <span class="badge rounded-pill bg-danger ms-auto">15</span>
            </a>
            <ul class="menu-sub">
                <li class="menu-item active">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Analytics">Diamond Master</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Layouts -->
        <li
            class="menu-item   {{ request()->is('admin/shades*') ||
            request()->is('admin/shapes*') ||
            request()->is('admin/sizes*') ||
            request()->is('admin/clarity*') ||
            request()->is('admin/symmetry*') ||
            request()->is('admin/flourescence*') ||
            request()->is('admin/fancyColor*') ||
            request()->is('admin/girdle*') ||
            request()->is('admin/culet*') ||
            request()->is('admin/keyToSymbols*') ||
            request()->is('admin/lab*') ||
            request()->is('admin/polish*') ||
            request()->is('admin/diamond-color*') ||
            request()->is('admin/cut*')
                ? 'active open'
                : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-layout"></i>
                <div class="text-truncate" data-i18n="Layouts">Diamond Masters</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('shades.index') ? 'active' : '' }}">
                    <a href="{{ route('shades.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Without menu">Shades</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('shapes.index') ? 'active' : '' }}">
                    <a href="{{ route('shapes.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Shape">Shape</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('sizes.index') ? 'active' : '' }}">
                    <a href="{{ route('sizes.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Size">Size</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('symmetry.index') ? 'active' : '' }}">
                    <a href="{{ route('symmetry.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Account">Symmetry</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('fancyColor.index') ? 'active' : '' }} ">
                    <a href="{{ route('fancyColor.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Account">Fancy Color Overtones</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('flourescence.index') ? 'active' : '' }}">
                    <a href="{{ route('flourescence.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Fluorescence">Fluorescence</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('girdle.index') ? 'active' : '' }}">
                    <a href="{{ route('girdle.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Girdle">Girdle</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('keytosymbols.index') ? 'active' : '' }}">
                    <a href="{{ route('keytosymbols.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="KeyToSymbol">Key to Symbols</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('diamondlab.index') ? 'active' : '' }}">
                    <a href="{{ route('diamondlab.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Lab">Lab</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('diamondpolish.index') ? 'active' : '' }}">
                    <a href="{{ route('diamondpolish.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Polish">Polish</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('clarity.index') ? 'active' : '' }}">
                    <a href="{{ route('clarity.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Clarity">Clarity</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('color.index') ? 'active' : '' }}">
                    <a href="{{ route('color.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Color">Color</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('culet.index') ? 'active' : '' }}">
                    <a href="{{ route('culet.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Culet">Culet</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('cut.index') ? 'active' : '' }}">
                    <a href="{{ route('cut.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="Cut">Cut</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('girdle.index') ? 'active' : '' }}">
                    <a href="#" class="menu-link">
                        <div class="text-truncate" data-i18n="fancyColor">Fancy Color Intensity</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Vedore -->
        <li class="menu-item {{ request()->is('/vendors') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-smile"></i>
                <div class="text-truncate" data-i18n="Vendor">Vendor</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item active">
                    <a href="{{ route('vendor.index') }}" class="menu-link">
                        <div class="text-truncate" data-i18n="vendor">Vendor List</div>
                    </a>
                </li>
            </ul>
        </li>

    </ul>
</aside>
