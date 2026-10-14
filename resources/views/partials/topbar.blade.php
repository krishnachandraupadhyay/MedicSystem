@php
    $authUser = Auth::user();
    $roleSlug = $authUser->role_slug ?? 'laboratory';
    $roleTitle = $authUser->display_role ?? 'Lab Admin';
    $initials = $authUser->initials ?? 'LA';
    $isBM = $authUser->is_branch_manager ?? false;
@endphp

<!-- UNIFIED TOPBAR HEADER -->
<header class="topbar">
    <div>
        <button class="toggle-btn" onclick="toggleSidebar()" title="Toggle Sidebar">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>

    <div class="topbar-actions">
        <button class="icon-circle-btn" title="Notifications">
            <i class="fa-regular fa-bell"></i>
            <span class="badge-dot-red"></span>
        </button>

        <button class="icon-circle-btn" title="Messages">
            <i class="fa-regular fa-comment-dots"></i>
        </button>

        <!-- Profile Badge with Dropdown -->
        <div class="user-dropdown-wrap" id="userDropdownWrap">
            <div class="user-profile-btn" id="userProfileBtn" onclick="toggleProfileDropdown(event)" title="{{ $authUser->name }} ({{ $roleTitle }})">
                <div class="user-avatar-circle">
                    {{ $initials }}
                </div>
                <i class="fa-solid fa-chevron-down chevron-icon" style="font-size: 10px; color: #94a3b8; margin-left: 2px;"></i>
            </div>

            <!-- Dropdown Content -->
            <div class="profile-dropdown-menu" id="profileDropdownMenu">
                <div class="dropdown-item-header" style="{{ $isBM ? 'background: #ecfdf5; border-color: #a7f3d0;' : '' }}">
                    <div style="display: flex; align-items: flex-start; gap: 10px; width: 100%;">
                        <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0;">
                            {{ $initials }}
                        </div>
                        <div style="display: flex; flex-direction: column; text-align: left; gap: 2px; flex: 1;">
                            <span style="font-size: 13.5px; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ $authUser->name }}</span>
                            <span style="font-size: 11.5px; font-weight: 600; color: #059669;">{{ $roleTitle }}</span>
                            @if($authUser->branch_name)
                                <div style="margin-top: 5px; display: inline-flex; align-items: center; gap: 4px; background: #ffffff; border: 1px solid #6ee7b7; padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 700; color: #047857; width: fit-content;" title="Branch Code: {{ $authUser->branch_short }}">
                                    <i class="fa-solid fa-code-branch" style="font-size: 9px; color: #059669;"></i>
                                    <span>{{ $authUser->branch_name }}</span>
                                    @if($authUser->branch_short && $authUser->branch_short !== $authUser->branch_name)
                                        <span style="font-size: 9.5px; opacity: 0.8; font-weight: 600;">({{ $authUser->branch_short }})</span>
                                    @endif
                                </div>
                            @endif
                            @if($authUser->hospital_name)
                                <span style="font-size: 10.5px; color: #64748b; margin-top: 3px;">
                                    <i class="fa-solid fa-hospital" style="margin-right: 3px; font-size: 9.5px;"></i>{{ $authUser->hospital_name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <a href="{{ url($roleSlug . '/profile') }}" class="dropdown-item-btn dropdown-menu-item">
                    <i class="fa-regular fa-user" style="font-size: 14px; color: #64748b;"></i>
                    <span>My Profile</span>
                </a>

                <div class="dropdown-divider"></div>

                <form action="{{ route('logout') }}" method="POST" style="margin: 0; width: 100%;">
                    @csrf
                    <button type="submit" class="dropdown-item-btn dropdown-menu-item logout-btn-item logout">
                        <i class="fa-solid fa-arrow-right-from-bracket" style="font-size: 14px;"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<style>
/* Robust Topbar Profile Dropdown CSS */
.user-dropdown-wrap { position: relative; }
.user-profile-btn {
    display: flex; align-items: center; gap: 8px; padding: 4px 10px 4px 4px;
    border-radius: 30px; cursor: pointer; transition: all 0.2s ease;
    background: #ffffff; border: 1px solid #e2e8f0;
}
.user-profile-btn:hover { background: #f8fafc; border-color: #cbd5e1; }
.user-avatar-circle {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    color: #ffffff; font-weight: 800; font-size: 13px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25); flex-shrink: 0;
}
.chevron-icon { font-size: 11px; color: #64748b; margin-right: 4px; transition: transform 0.2s ease; }
.user-profile-btn.active .chevron-icon,
.user-dropdown-wrap.open .chevron-icon { transform: rotate(180deg); }

.profile-dropdown-menu {
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    width: 270px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
    border: 1px solid #f1f5f9;
    padding: 8px;
    display: none;
    z-index: 1000;
}
.profile-dropdown-menu.show {
    display: block !important;
    animation: topbarDropdownFade 0.18s ease-out;
}
@keyframes topbarDropdownFade {
    from { opacity: 0; transform: translateY(-6px); }
    to { opacity: 1; transform: translateY(0); }
}
.dropdown-item-header {
    padding: 10px 12px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 8px;
    margin-bottom: 6px;
}
.dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }
.dropdown-item-btn, .dropdown-menu-item {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 12px;
    border: none;
    background: transparent;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    text-align: left;
    text-decoration: none;
    box-sizing: border-box;
}
.dropdown-item-btn:hover, .dropdown-menu-item:hover {
    background: #f1f5f9;
    color: #4f46e5;
}
.dropdown-item-btn.logout-btn-item, .dropdown-menu-item.logout {
    color: #ef4444;
}
.dropdown-item-btn.logout-btn-item:hover, .dropdown-menu-item.logout:hover {
    background: #fee2e2;
    color: #ef4444;
}
</style>

<script>
if (typeof toggleSidebar !== 'function') {
    window.toggleSidebar = function() {
        const sb = document.getElementById('sidebar');
        if (sb) sb.classList.toggle('collapsed');
    };
}
if (typeof toggleProfileDropdown !== 'function') {
    window.toggleProfileDropdown = function(e) {
        if (e) e.stopPropagation();
        const menu = document.getElementById('profileDropdownMenu');
        const btn = document.getElementById('userProfileBtn');
        const wrap = document.getElementById('userDropdownWrap');
        if (!menu) return;
        const isOpen = menu.classList.contains('show');
        if (isOpen) {
            menu.classList.remove('show');
            if (btn) btn.classList.remove('active');
            if (wrap) wrap.classList.remove('open');
        } else {
            menu.classList.add('show');
            if (btn) btn.classList.add('active');
            if (wrap) wrap.classList.add('open');
        }
    };
}
window.addEventListener('click', function(e) {
    const wrap = document.getElementById('userDropdownWrap');
    const menu = document.getElementById('profileDropdownMenu');
    const btn = document.getElementById('userProfileBtn');
    if (menu && wrap && !wrap.contains(e.target)) {
        menu.classList.remove('show');
        if (btn) btn.classList.remove('active');
        wrap.classList.remove('open');
    }
});
</script>
