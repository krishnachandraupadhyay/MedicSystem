@php
    $user = Auth::user();
    $rSlug = $user->role_slug ?? 'laboratory';
    $hospitalModel = $hospital ?? ($user->hospital ?? null);
    $hospitalName = $hospitalModel->name ?? ($user->hospital_name ?? 'Diagnostic Lab');
@endphp

<!-- SIDEBAR -->
<aside class="sidebar" id="sidebar">
    <div class="brand-block">
        <div class="brand-logo-icon">
            <i class="fa-solid fa-flask-vial"></i>
        </div>
        <div class="brand-info">
            <h2>LABORATORY SYSTEM</h2>
            <p>{{ $hospitalName }}</p>
        </div>
    </div>

    <div class="sidebar-scroll-content">
        <div class="menu-heading">MAIN MENU</div>
        <ul class="nav-menu">
            <li>
                <a href="{{ url($rSlug . '/dashboard') }}" class="nav-link {{ request()->is('*dashboard*') ? 'active' : '' }}">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span class="nav-title">Dashboard</span>
                </a>
            </li>

            @if($user && $user->canAccessModule('tests'))
                <li>
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('*sample-collection*') ? 'active' : '' }}">
                        <i class="fa-solid fa-vials"></i>
                        <span class="nav-title">Sample Collection</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url($rSlug . '/test-categories') }}" class="nav-link {{ request()->is('*test-categories*') ? 'active' : '' }}">
                        <i class="fa-solid fa-folder-tree"></i>
                        <span class="nav-title">Test Categories</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url($rSlug . '/tests') }}" class="nav-link {{ (request()->is('*tests*') && !request()->is('*test-categories*') && !request()->is('*test-fees*')) ? 'active' : '' }}">
                        <i class="fa-solid fa-vials"></i>
                        <span class="nav-title">Lab Tests</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url($rSlug . '/test-fees') }}" class="nav-link {{ request()->is('*test-fees*') ? 'active' : '' }}">
                        <i class="fa-solid fa-indian-rupee-sign"></i>
                        <span class="nav-title">Test Fees &amp; Pricing</span>
                    </a>
                </li>
            @endif

            @if($user && $user->canAccessModule('staff'))
                <li>
                    <a href="{{ url($rSlug . '/staff') }}" class="nav-link {{ request()->is('*staff*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-doctor"></i>
                        <span class="nav-title">Lab Staff</span>
                    </a>
                </li>
            @endif

            @if($user && $user->canAccessModule('reports'))
                <li>
                    <a href="javascript:void(0)" class="nav-link {{ request()->is('*reports*') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-waveform"></i>
                        <span class="nav-title">Diagnostic Reports</span>
                    </a>
                </li>
            @endif

            <!-- Branch Management (Administrative Only) -->
            @if(in_array($user->role ?? '', ['Super Admin', 'Laboratory Admin', 'Admin']) && (($hospitalModel && $hospitalModel->enable_multi_branch) || $user->canAccessModule('branches')))
                <div class="menu-heading">BRANCH MANAGEMENT</div>
                <li>
                    <a href="{{ url($rSlug . '/branches') }}" class="nav-link {{ (request()->is('*branches*') && !request()->is('*branch-managers*')) ? 'active' : '' }}">
                        <i class="fa-solid fa-building"></i>
                        <span class="nav-title">Branches</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url($rSlug . '/branch-managers') }}" class="nav-link {{ request()->is('*branch-managers*') ? 'active' : '' }}">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="nav-title">Branch Managers</span>
                    </a>
                </li>
            @endif

            <!-- Roles & Access (Administrative Only) -->
            @if(in_array($user->role ?? '', ['Super Admin', 'Laboratory Admin', 'Admin']))
                <div class="menu-heading">ROLES & ACCESS</div>
                <li>
                    <a href="{{ url($rSlug . '/roles') }}" class="nav-link {{ (request()->is('*roles*') && !request()->is('*permissions*')) ? 'active' : '' }}">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="nav-title">Roles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ url($rSlug . '/permissions') }}" class="nav-link {{ request()->is('*permissions*') ? 'active' : '' }}">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="nav-title">Permissions</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span class="nav-title">Log Out</span>
            </button>
        </form>
    </div>
</aside>
