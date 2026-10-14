<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Permissions - {{ $hospital->name ?? 'Laboratory System' }}</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --sidebar-bg: #0e1526;
            --sidebar-active-bg: #1c263f;
            --sidebar-hover-bg: #151e36;
            --sidebar-text: #7f8ea3;
            --sidebar-text-active: #ffffff;
            --primary-btn: #4f46e5;
            --primary-btn-hover: #4338ca;
            --body-bg: #f8fafc;
            --card-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: #1f2937;
            min-height: 100vh;
            display: flex;
            overflow-x: hidden;
        }

        /* ------------------ SIDEBAR ------------------ */
        .sidebar {
            width: 255px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            height: 100vh;
            max-height: 100vh;
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .sidebar.collapsed { width: 78px; }
        .sidebar.collapsed .brand-info,
        .sidebar.collapsed .menu-heading,
        .sidebar.collapsed .nav-title { display: none; }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 22px;
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            z-index: 10;
        }

        .brand-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
            flex-shrink: 0;
        }

        .brand-info h2 { font-size: 14.5px; font-weight: 800; color: #ffffff; letter-spacing: 0.5px; }
        .brand-info p { font-size: 11px; color: #64748b; margin-top: 2px; }

        .sidebar-scroll-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
            scrollbar-width: thin;
        }
        .sidebar-scroll-content::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll-content::-webkit-scrollbar-track { background: transparent; }
        .sidebar-scroll-content::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.15); border-radius: 4px; }
        .sidebar-scroll-content::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.3); }

        .menu-heading { font-size: 11px; font-weight: 700; color: #475569; padding: 12px 24px 8px; text-transform: uppercase; letter-spacing: 0.6px; flex-shrink: 0; }
        .nav-menu { list-style: none; padding: 0 12px 16px; display: flex; flex-direction: column; gap: 2px; }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
        }

        .nav-link:hover { background-color: var(--sidebar-hover-bg); color: #cbd5e1; }
        .nav-link.active { background-color: #4f46e5; color: #ffffff; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35); }
        .nav-link i { width: 24px; font-size: 16px; margin-right: 12px; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            flex-shrink: 0;
            margin-top: auto;
            background-color: var(--sidebar-bg);
            z-index: 10;
        }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 14px;
            background: rgba(239, 68, 68, 0.1); color: #f87171; border: none; border-radius: 8px; font-weight: 600; font-size: 13px; cursor: pointer;
        }
        .btn-logout:hover { background: #ef4444; color: #ffffff; }

        /* ------------------ MAIN WRAPPER ------------------ */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            min-height: 100vh;
        }
        .topbar {
            height: 68px;
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
        }
        .toggle-btn {
            background: transparent;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .toggle-btn:hover { background: #f1f5f9; color: #0f172a; }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .icon-circle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 14.5px;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }
        .icon-circle-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }
        .badge-dot-red {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 6.5px;
            height: 6.5px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
        }

        .user-dropdown-wrap { position: relative; }
        .user-profile-btn {
            display: flex; align-items: center; gap: 10px; padding: 4px 12px 4px 4px; border-radius: 30px; cursor: pointer;
            background: #ffffff; border: 1px solid #e2e8f0; transition: all 0.2s ease;
        }
        .user-profile-btn:hover, .user-profile-btn.active { background: #f8fafc; border-color: #cbd5e1; }
        .user-avatar-circle {
            width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: #ffffff;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25); flex-shrink: 0;
        }
        .chevron-icon { font-size: 10px; color: #94a3b8; margin-left: 2px; transition: transform 0.2s ease; }
        .user-profile-btn.active .chevron-icon { transform: rotate(180deg); }

        .profile-dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px); width: 270px; background: #ffffff; border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08); border: 1px solid #f1f5f9;
            padding: 8px; display: none; z-index: 1000;
        }
        .profile-dropdown-menu.show { display: block; animation: dropdownFadeIn 0.15s ease-out; }
        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .dropdown-item-header {
            padding: 10px 12px 8px; border-bottom: 1px solid #f1f5f9; margin-bottom: 4px; display: flex; align-items: center; gap: 10px;
        }
        .dropdown-item-header i { font-size: 16px; color: #059669; }
        .dropdown-item-header-text { display: flex; flex-direction: column; overflow: hidden; }
        .dropdown-facility-name { font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dropdown-facility-role { font-size: 11px; color: #059669; font-weight: 600; }
        .dropdown-menu-item {
            display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; font-size: 13px; font-weight: 600;
            color: #334155; text-decoration: none; transition: background 0.15s ease; width: 100%; border: none; background: transparent; cursor: pointer; text-align: left;
        }
        .dropdown-menu-item:hover { background: #f8fafc; color: #0f172a; }
        .dropdown-menu-item.logout { color: #ef4444; }
        .dropdown-menu-item.logout:hover { background: #fef2f2; }
        .dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }

        /* ------------------ CONTENT AREA ------------------ */
        .content-area {
            padding: 32px;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        .toast-msg {
            background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 14px 18px; border-radius: 12px;
            font-size: 13.5px; font-weight: 600; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;
        }
        .toast-error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }

        .page-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
            flex-wrap: wrap;
            gap: 16px;
        }
        .page-heading h1 { font-size: 23px; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; }
        .page-heading p { font-size: 13px; color: #64748b; margin-top: 4px; }

        .btn-link-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #334155;
            font-weight: 700;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-link-action:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: #0f172a;
        }

        /* ------------------ ROLE SELECTOR TABS ------------------ */
        .role-selector-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 20px 24px;
            margin-bottom: 24px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .selector-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .selector-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .role-pills-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 16px;
            border-radius: 12px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #334155;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .role-pill:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .role-pill.active {
            background: #ecfdf5;
            border-color: #059669;
            color: #065f46;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
        }
        .role-pill .count-tag {
            font-size: 11px;
            padding: 2px 7px;
            border-radius: 20px;
            background: #e2e8f0;
            color: #475569;
            font-weight: 800;
        }
        .role-pill.active .count-tag {
            background: #059669;
            color: #ffffff;
        }

        /* ------------------ PERMISSIONS FORM CONTAINER ------------------ */
        .perm-form-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            margin-bottom: 24px;
        }
        .perm-form-head {
            padding: 20px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }
        .perm-role-focus h2 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .perm-role-focus p {
            font-size: 12.5px;
            color: #64748b;
            margin-top: 3px;
        }
        .perm-quick-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-quick-toggle {
            padding: 7px 14px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-quick-toggle:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .perm-body-grid {
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .module-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
            transition: all 0.2s;
        }
        .module-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
        .module-card-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .module-title-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .module-icon-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 17px;
            flex-shrink: 0;
        }
        .module-info h5 {
            font-size: 14px;
            font-weight: 800;
            color: #0f172a;
        }
        .module-info p {
            font-size: 12px;
            color: #64748b;
        }

        .module-actions-row {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 12px;
        }
        .action-toggle-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            transition: all 0.2s;
        }
        .action-toggle-item:hover {
            border-color: #cbd5e1;
            background: #ffffff;
        }
        .action-toggle-item.checked {
            background: #f0fdf4;
            border-color: #86efac;
        }
        .action-name {
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
        }

        /* TOGGLE SWITCH */
        .switch-wrap {
            position: relative;
            display: inline-block;
            width: 38px;
            height: 22px;
            flex-shrink: 0;
        }
        .switch-wrap input { opacity: 0; width: 0; height: 0; }
        .switch-slider {
            position: absolute; cursor: pointer; inset: 0; background-color: #cbd5e1; border-radius: 34px; transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .switch-slider:before {
            position: absolute; content: ""; height: 16px; width: 16px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        input:checked + .switch-slider { background-color: #059669; }
        input:checked + .switch-slider:before { transform: translateX(16px); }

        .perm-form-foot {
            padding: 18px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }
        .btn-submit-perm {
            padding: 11px 26px;
            border-radius: 10px;
            border: none;
            background: #059669;
            color: #ffffff;
            cursor: pointer;
            font-weight: 700;
            font-size: 13.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3);
            transition: all 0.2s;
        }
        .btn-submit-perm:hover {
            background: #047857;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

@php
    $rSlug = Auth::user()->role_slug ?? 'laboratory';
@endphp

    <!-- SIDEBAR -->
    @include('partials.laboratory_sidebar')

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        @include('partials.topbar')

        <main class="content-area">
            @if(session('success'))
                <div class="toast-msg">
                    <div><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}</div>
                    <button style="background:none; border:none; cursor:pointer; color:inherit;" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="toast-msg toast-error">
                    <div><i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i> {{ session('error') }}</div>
                    <button style="background:none; border:none; cursor:pointer; color:inherit;" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            <!-- Page Header -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Role Permissions &amp; Access Control</h1>
                    <p>Configure granular access control (Patients, Staff, Attendance, Branches, Tests, Reports) for each lab role.</p>
                </div>

                <div style="display: flex; gap: 10px;">
                    <a href="{{ url($rSlug . '/roles') }}" class="btn-link-action">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Manage Roles</span>
                    </a>
                </div>
            </div>

            <!-- ROLE SELECTOR TABS -->
            <div class="role-selector-card">
                <div class="selector-header">
                    <div class="selector-title">
                        <i class="fa-solid fa-user-tag" style="color: #059669;"></i>
                        <span>Select Role to Configure Permissions:</span>
                    </div>
                    @if($selectedRole)
                        <span style="font-size: 12px; color: #64748b;">
                            Role Slug: <b style="color: #0f172a;">{{ $selectedRole->slug }}</b>
                        </span>
                    @endif
                </div>

                @if($roles->isEmpty())
                    <div style="padding: 24px; text-align: center; color: #94a3b8;">
                        <i class="fa-solid fa-user-shield" style="font-size: 32px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                        No roles have been created yet. Please <a href="{{ url($rSlug . '/roles') }}" style="color: #059669; font-weight: 700;">create a role first</a>.
                    </div>
                @else
                    <div class="role-pills-row">
                        @foreach($roles as $r)
                            <a href="{{ url($rSlug . '/permissions?role_id=' . $r->id) }}" class="role-pill {{ ($selectedRole && $selectedRole->id === $r->id) ? 'active' : '' }}">
                                <i class="fa-solid fa-user-gear"></i>
                                <span>{{ $r->name }}</span>
                                <span class="count-tag">{{ $r->active_permissions_count }} Active</span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($selectedRole)
                <!-- PERMISSIONS FORM -->
                <form action="{{ url($rSlug . '/permissions/' . $selectedRole->id) }}" method="POST">
                    @csrf
                    <div class="perm-form-card">
                        <div class="perm-form-head">
                            <div class="perm-role-focus">
                                <h2>
                                    <i class="fa-solid fa-shield-halved" style="color: #059669;"></i>
                                    <span>Permissions for: <span style="color: #059669;">{{ $selectedRole->name }}</span></span>
                                </h2>
                                <p>{{ $selectedRole->description ?: 'Enable or disable granular module actions for this role.' }}</p>
                            </div>

                            <div class="perm-quick-actions">
                                <button type="button" class="btn-quick-toggle" onclick="toggleAllPermissions(true)">
                                    <i class="fa-solid fa-check-double" style="margin-right: 4px;"></i> Select All
                                </button>
                                <button type="button" class="btn-quick-toggle" onclick="toggleAllPermissions(false)">
                                    <i class="fa-solid fa-rotate-left" style="margin-right: 4px;"></i> Reset to OFF
                                </button>
                            </div>
                        </div>

                        <div class="perm-body-grid">
                            @foreach($modules as $modKey => $module)
                                <div class="module-card">
                                    <div class="module-card-head">
                                        <div class="module-title-box">
                                            <div class="module-icon-wrap" style="background: {{ $module['badge_color'] }};">
                                                <i class="{{ $module['icon'] }}"></i>
                                            </div>
                                            <div class="module-info">
                                                <h5>{{ $module['label'] }}</h5>
                                                <p>{{ $module['description'] }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <button type="button" class="btn-quick-toggle" onclick="toggleModule('{{ $modKey }}')">
                                                Toggle Module
                                            </button>
                                        </div>
                                    </div>

                                    <div class="module-actions-row">
                                        @foreach($module['actions'] as $actKey => $actLabel)
                                            @php
                                                $isEnabled = $selectedRole->hasPermission($modKey, $actKey);
                                            @endphp
                                            <div class="action-toggle-item {{ $isEnabled ? 'checked' : '' }}">
                                                <span class="action-name">{{ $actLabel }}</span>
                                                <label class="switch-wrap">
                                                    <input type="checkbox"
                                                           name="permissions[{{ $modKey }}][{{ $actKey }}]"
                                                           value="1"
                                                           class="perm-checkbox mod-{{ $modKey }}"
                                                           {{ $isEnabled ? 'checked' : '' }}
                                                           onchange="handleToggleStyle(this)">
                                                    <span class="switch-slider"></span>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="perm-form-foot">
                            <span style="font-size: 12.5px; color: #64748b;">
                                <i class="fa-solid fa-info-circle"></i> @if(Auth::user()->is_branch_manager) View Only Mode: Branch Managers cannot edit role permissions. @else Changes save hone ke baad is role se jude sabhi users par instantly apply honge. @endif
                            </span>
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ url($rSlug . '/roles') }}" class="btn-link-action" style="padding: 10px 18px;">@if(Auth::user()->is_branch_manager) Back to Roles @else Cancel @endif</a>
                                @if(!Auth::user()->is_branch_manager)
                                    <button type="submit" class="btn-submit-perm">
                                        <i class="fa-solid fa-floppy-disk"></i>
                                        <span>Save Permissions for {{ $selectedRole->name }}</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const m = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (!m) return;
            const isOpen = m.classList.contains('show');
            if (isOpen) {
                m.classList.remove('show');
                if (btn) btn.classList.remove('active');
            } else {
                m.classList.add('show');
                if (btn) btn.classList.add('active');
            }
        }

        window.addEventListener('click', function(e) {
            const wrap = document.getElementById('userDropdownWrap');
            const menu = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (menu && wrap && !wrap.contains(e.target)) {
                menu.classList.remove('show');
                if (btn) btn.classList.remove('active');
            }
        });

        function handleToggleStyle(input) {
            const container = input.closest('.action-toggle-item');
            if (container) {
                if (input.checked) {
                    container.classList.add('checked');
                } else {
                    container.classList.remove('checked');
                }
            }
        }

        function toggleAllPermissions(enable) {
            document.querySelectorAll('.perm-checkbox').forEach(chk => {
                chk.checked = enable;
                handleToggleStyle(chk);
            });
        }

        function toggleModule(modKey) {
            const checks = document.querySelectorAll('.mod-' + modKey);
            if (checks.length === 0) return;
            const anyUnchecked = Array.from(checks).some(c => !c.checked);
            checks.forEach(chk => {
                chk.checked = anyUnchecked;
                handleToggleStyle(chk);
            });
        }
    </script>
    @include('partials.validation_popup')
</body>
</html>
