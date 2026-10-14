<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Branches - Hospital System</title>
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
            --body-bg: #f5f7fb;
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

        /* Master Submenu Navigation */
        .nav-item-dropdown { position: relative; }
        .nav-dropdown-toggle { cursor: pointer; }
        .submenu-arrow { font-size: 11px; transition: transform 0.25s ease; margin-left: auto; }
        .nav-item-dropdown.open .submenu-arrow { transform: rotate(90deg); }
        .nav-submenu { list-style: none; padding: 4px 0 6px 36px; display: none; }
        .nav-submenu li { margin-bottom: 2px; }
        .sub-link { display: flex; align-items: center; gap: 10px; padding: 8px 12px; border-radius: 8px; color: #94a3b8; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.2s; }
        .sub-link i { font-size: 7px; color: #64748b; }
        .sub-link:hover { color: #ffffff; background: #1A2238; }
        .sub-link.active { color: #60a5fa; font-weight: 700; background: rgba(96, 165, 250, 0.1); }
        .sub-link.active i { color: #60a5fa; font-size: 8px; }
        .sidebar.collapsed .nav-submenu { display: none !important; }

        /* Super Admin Topbar Action Icons & Menu */
        .icon-circle-btn { width: 38px; height: 38px; border-radius: 50%; background: #f8fafc; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: center; color: #64748b; font-size: 14.5px; cursor: pointer; position: relative; transition: all 0.2s ease; }
        .icon-circle-btn:hover { background: #f1f5f9; color: #0f172a; }
        .badge-dot-red { position: absolute; top: 8px; right: 8px; width: 6.5px; height: 6.5px; background-color: #ef4444; border-radius: 50%; border: 1.5px solid #ffffff; }
        .dropdown-user-header { display: flex; align-items: center; gap: 10px; padding: 10px 12px; background: #f8fafc; border-radius: 8px; margin-bottom: 6px; }
        .dropdown-avatar-circle { width: 32px; height: 32px; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 12px; flex-shrink: 0; }
        .dropdown-user-details { display: flex; flex-direction: column; overflow: hidden; }
        .dropdown-role-name { font-size: 13.5px; font-weight: 700; color: #0f172a; line-height: 1.2; }
        .dropdown-role-sub { font-size: 11.5px; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .dropdown-divider { height: 1px; background: #f1f5f9; margin: 4px 0; }
        .dropdown-item-btn { width: 100%; display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: none; background: transparent; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ef4444; cursor: pointer; transition: all 0.2s; }
        .dropdown-item-btn:hover { background: #fef2f2; }

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
            height: 72px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid #eef2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 40;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }
        .toggle-btn {
            background: #f8fafc; border: 1px solid #e2e8f0; width: 38px; height: 38px; border-radius: 10px;
            cursor: pointer; color: #475569; display: flex; align-items: center; justify-content: center;
        }
        .topbar-actions { display: flex; align-items: center; gap: 14px; }

        /* TOPBAR PROFILE & DROPDOWN */
        .user-dropdown-wrap {
            position: relative;
        }
        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 4px 10px 4px 4px;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #ffffff;
            border: 1px solid #e2e8f0;
        }
        .user-profile-btn:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }
        .user-avatar-circle {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 13px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
            flex-shrink: 0;
        }
        .chevron-icon {
            font-size: 11px;
            color: #64748b;
            transition: transform 0.2s ease;
        }
        .user-dropdown-wrap.open .chevron-icon {
            transform: rotate(180deg);
        }

        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 270px;
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 12px 30px -4px rgba(15, 23, 42, 0.15), 0 4px 12px -2px rgba(15, 23, 42, 0.08);
            border: 1px solid #e2e8f0;
            padding: 8px;
            display: none;
            z-index: 1000;
        }
        .profile-dropdown-menu.show {
            display: block;
            animation: dropdownFade 0.18s ease-out;
        }
        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 1) Facility Header: Apex Diagnostic Center (Lab Admin) */
        .dropdown-item-header {
            padding: 10px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 10px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dropdown-item-header i {
            font-size: 17px;
            color: #059669;
            flex-shrink: 0;
        }
        .dropdown-item-header-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }
        .dropdown-facility-name {
            font-size: 13px;
            font-weight: 800;
            color: #065f46;
            line-height: 1.3;
            word-break: break-word;
        }
        .dropdown-facility-role {
            font-size: 11.5px;
            color: #047857;
            font-weight: 700;
            margin-top: 2px;
        }

        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        /* 2) & 3) Menu Options */
        .dropdown-menu-item {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border: none;
            background: transparent;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease;
            text-decoration: none;
            text-align: left;
        }
        .dropdown-menu-item:hover {
            background: #f8fafc;
            color: #0f172a;
        }
        .dropdown-menu-item.logout {
            color: #ef4444;
        }
        .dropdown-menu-item.logout:hover {
            background: #fee2e2;
            color: #dc2626;
        }
        .dropdown-menu-item i {
            font-size: 15px;
            width: 18px;
            text-align: center;
            color: #64748b;
        }
        .dropdown-menu-item.logout i {
            color: #ef4444;
        }

        .content-area { padding: 28px; max-width: 1600px; width: 100%; margin: 0 auto; }

        .page-header-row {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
        }
        .page-heading h1 { font-size: 23px; font-weight: 800; color: #0f172a; }
        .page-heading p { font-size: 13.5px; color: #64748b; margin-top: 4px; }

        .btn-primary-add {
            background: #059669; color: #ffffff; border: none; padding: 11px 20px; border-radius: 10px;
            font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25); transition: all 0.2s;
        }
        .btn-primary-add:hover { background: #047857; transform: translateY(-1px); }

        .toast-msg {
            background: #10b981; color: #ffffff; padding: 12px 18px; border-radius: 10px; font-size: 13.5px;
            font-weight: 600; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
        }
        .toast-error { background: #ef4444; }

        /* PERMISSION LOCKED NOTICE */
        .locked-banner-card {
            background: #fffbeb; border: 1.5px dashed #f59e0b; border-radius: 16px; padding: 36px; text-align: center;
            margin-bottom: 24px;
        }
        .locked-icon-box {
            width: 60px; height: 60px; background: #fef3c7; color: #d97706; border-radius: 50%; display: flex;
            align-items: center; justify-content: center; font-size: 26px; margin: 0 auto 16px;
        }
        .locked-banner-card h3 { font-size: 18px; font-weight: 800; color: #92400e; margin-bottom: 8px; }
        .locked-banner-card p { font-size: 14px; color: #b45309; max-width: 580px; margin: 0 auto 20px; line-height: 1.5; }
        .btn-unlock-perm {
            background: #d97706; color: #ffffff; padding: 10px 22px; border-radius: 10px; text-decoration: none;
            font-weight: 700; font-size: 13.5px; display: inline-flex; align-items: center; gap: 8px;
        }

        /* TABLE CARD */
        .table-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); overflow: hidden;
        }
        .card-topbar {
            padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        }
        .card-title-badge { display: flex; align-items: center; gap: 10px; }
        .card-title-badge h2 { font-size: 16px; font-weight: 700; color: #1e293b; }
        .count-bubble { background: #eef2ff; color: #4f46e5; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

        .search-box-wrap { position: relative; min-width: 260px; }
        .search-box-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        .search-box-input {
            width: 100%; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px 0 38px; font-size: 13.5px; color: #1e293b; outline: none;
        }
        .search-box-input:focus { background: #ffffff; border-color: #6366f1; }

        .table-wrap { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th {
            background: #f8fafc; color: #475569; font-size: 11.5px; font-weight: 700; letter-spacing: 0.6px;
            text-transform: uppercase; padding: 12px 18px; border-bottom: 1px solid #eef2f7;
        }
        .data-table td { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #334155; vertical-align: middle; }

        .branch-avatar {
            width: 36px; height: 36px; border-radius: 10px; background: #eef2ff; color: #4f46e5; display: flex;
            align-items: center; justify-content: center; font-size: 16px;
        }
        .branch-cell { display: flex; align-items: center; gap: 12px; }

        .badge-status {
            font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px;
        }
        .badge-status.active { background: #dcfce7; color: #15803d; }
        .badge-status.inactive { background: #fee2e2; color: #b91c1c; }

        .action-btns { display: flex; align-items: center; gap: 8px; }
        .btn-action-icon {
            width: 32px; height: 32px; border-radius: 8px; border: 1px solid #e2e8f0; background: #ffffff;
            color: #64748b; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px; transition: all 0.2s;
        }
        .btn-action-icon:hover { background: #f8fafc; color: #1e293b; }
        .btn-action-icon.delete:hover { background: #fee2e2; color: #dc2626; border-color: #fca5a5; }

        /* MODAL */
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 100; padding: 20px;
        }
        .modal-box {
            background: #ffffff; width: 100%; max-width: 580px; border-radius: 16px; overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); animation: zoomIn 0.2s ease;
        }
        @keyframes zoomIn { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        .modal-head {
            padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;
        }
        .modal-head h3 { font-size: 17px; font-weight: 800; color: #0f172a; }
        .close-x-btn { background: none; border: none; font-size: 22px; color: #94a3b8; cursor: pointer; }
        .modal-content-pad { padding: 22px 24px; }
        .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field-item { display: flex; flex-direction: column; gap: 6px; }
        .field-item label { font-size: 12px; font-weight: 700; color: #475569; }
        .field-input {
            width: 100%; height: 42px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px; font-size: 13.5px; color: #1e293b; outline: none;
        }
        .field-input:focus { background: #ffffff; border-color: #6366f1; }
        .modal-action-bar {
            padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;
        }
        .btn-modal-cancel { padding: 9px 18px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; font-weight: 600; }
        .btn-modal-submit { padding: 9px 20px; border-radius: 8px; border: none; background: #4f46e5; color: #ffffff; cursor: pointer; font-weight: 700; }
    </style>
</head>
<body>

@php
    $user = Auth::user();
    $rSlug = $user->role_slug ?? 'laboratory';
    $isSuperAdmin = ($user->role === 'Super Admin' || $rSlug === 'superadmin');
    $isLabFacility = ! $isSuperAdmin && (($hospital && $hospital->medical_type === 'Laboratory') || str_contains(strtolower($user->role ?? ''), 'lab') || in_array($rSlug, ['laboratory', 'branch-manager', 'branch-admin']) || request()->is('*laboratory*'));
@endphp

    @if($isLabFacility)
        @include('partials.laboratory_sidebar')
    @else
        <!-- SIDEBAR -->
        <aside class="sidebar" id="sidebar">
        <div class="brand-block">
            <div class="brand-logo-icon">
                @if(Auth::user()->role === 'Super Admin' || Auth::user()->role_slug === 'superadmin')
                    <i class="fa-solid fa-hospital"></i>
                @elseif(Auth::user()->role_slug === 'laboratory')
                    <i class="fa-solid fa-flask-vial"></i>
                @else
                    <i class="fa-solid fa-hospital"></i>
                @endif
            </div>
            <div class="brand-info">
                @if(Auth::user()->role === 'Super Admin' || Auth::user()->role_slug === 'superadmin')
                    <h2>HOSPITAL SYSTEM</h2>
                    <p>Hospital Management Dashboard</p>
                @elseif(Auth::user()->role_slug === 'laboratory')
                    <h2>LABORATORY SYSTEM</h2>
                    <p>{{ $hospital->name ?? 'Diagnostic Lab' }}</p>
                @else
                    <h2>HOSPITAL SYSTEM</h2>
                    <p>Hospital Management</p>
                @endif
            </div>
        </div>

@php
    $user = Auth::user();
    $rSlug = $user->role_slug ?? 'laboratory';
    $isSuperAdmin = ($user->role === 'Super Admin' || $rSlug === 'superadmin');
    $isLabFacility = ! $isSuperAdmin && (($hospital && $hospital->medical_type === 'Laboratory') || str_contains(strtolower($user->role ?? ''), 'lab') || in_array($rSlug, ['laboratory', 'branch-manager', 'branch-admin']));
@endphp
        <div class="sidebar-scroll-content">
            @if($isSuperAdmin)
                <div class="menu-heading">MAIN MENU</div>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ url($rSlug . '/dashboard') }}" class="nav-link">
                            <i class="fa-solid fa-table-cells-large"></i>
                            <span class="nav-title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url($rSlug . '/addadmin') }}" class="nav-link">
                            <i class="fa-solid fa-user-shield"></i>
                            <span class="nav-title">Admin</span>
                        </a>
                    </li>
                    <li class="nav-item-dropdown">
                        <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle" onclick="toggleMasterSubmenu(this)">
                            <i class="fa-solid fa-layer-group"></i>
                            <span class="nav-title">Master</span>
                            <i class="fa-solid fa-chevron-right submenu-arrow"></i>
                        </a>
                        <ul class="nav-submenu">
                            <li>
                                <a href="{{ url($rSlug . '/master/medical-types') }}" class="sub-link">
                                    <i class="fa-solid fa-circle-dot"></i>
                                    <span>Medical Type</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url($rSlug . '/master/hospital-types') }}" class="sub-link">
                                    <i class="fa-solid fa-circle-dot"></i>
                                    <span>Hospital Type</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url($rSlug . '/master/laboratory-types') }}" class="sub-link">
                                    <i class="fa-solid fa-circle-dot"></i>
                                    <span>Laboratory Type</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url($rSlug . '/master/pharmacy-types') }}" class="sub-link">
                                    <i class="fa-solid fa-circle-dot"></i>
                                    <span>Pharmacy Type</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ url($rSlug . '/branches') }}" class="nav-link active">
                            <i class="fa-solid fa-building"></i>
                            <span class="nav-title">Branches</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url($rSlug . '/branch-managers') }}" class="nav-link">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="nav-title">Branch Managers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url($rSlug . '/admin-permission') }}" class="nav-link">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span class="nav-title">Admin Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-regular fa-calendar-check"></i>
                            <span class="nav-title">Attendance</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span class="nav-title">Staff</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-solid fa-users"></i>
                            <span class="nav-title">Members</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span class="nav-title">Invoices</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-solid fa-wallet"></i>
                            <span class="nav-title">Finances</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span class="nav-title">Analytics</span>
                        </a>
                    </li>
                </ul>
            @elseif($isLabFacility)
                <div class="menu-heading">MAIN MENU</div>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ url($rSlug . '/dashboard') }}" class="nav-link">
                            <i class="fa-solid fa-table-cells-large"></i>
                            <span class="nav-title">Dashboard</span>
                        </a>
                    </li>
                    @if(Auth::user()->canAccessModule('tests'))
                        <li>
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="fa-solid fa-vials"></i>
                                <span class="nav-title">Sample Collection</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url($rSlug . '/test-categories') }}" class="nav-link">
                                <i class="fa-solid fa-folder-tree"></i>
                                <span class="nav-title">Test Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url($rSlug . '/tests') }}" class="nav-link">
                                <i class="fa-solid fa-vials"></i>
                                <span class="nav-title">Lab Tests</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url($rSlug . '/test-fees') }}" class="nav-link">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                <span class="nav-title">Test Fees &amp; Pricing</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->canAccessModule('staff'))
                        <li>
                            <a href="{{ url($rSlug . '/staff') }}" class="nav-link">
                                <i class="fa-solid fa-user-doctor"></i>
                                <span class="nav-title">Lab Staff</span>
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->canAccessModule('reports'))
                        <li>
                            <a href="javascript:void(0)" class="nav-link">
                                <i class="fa-solid fa-file-waveform"></i>
                                <span class="nav-title">Diagnostic Reports</span>
                            </a>
                        </li>
                    @endif

                    <!-- Branch Management (Administrative Only) -->
                    @if(in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin']) && (($hospital && $hospital->enable_multi_branch) || Auth::user()->canAccessModule('branches')))
                        <div class="menu-heading">BRANCH MANAGEMENT</div>
                        <li>
                            <a href="{{ url($rSlug . '/branches') }}" class="nav-link active">
                                <i class="fa-solid fa-building"></i>
                                <span class="nav-title">Branches</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url($rSlug . '/branch-managers') }}" class="nav-link">
                                <i class="fa-solid fa-user-tie"></i>
                                <span class="nav-title">Branch Managers</span>
                            </a>
                        </li>
                    @endif

                    @if(in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin']))
                        <div class="menu-heading">ROLES & ACCESS</div>
                        <li>
                            <a href="{{ url($rSlug . '/roles') }}" class="nav-link">
                                <i class="fa-solid fa-user-shield"></i>
                                <span class="nav-title">Roles</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url($rSlug . '/permissions') }}" class="nav-link">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span class="nav-title">Permissions</span>
                            </a>
                        </li>
                    @endif
                </ul>
            @else
                <div class="menu-heading">MAIN MENU</div>
                <ul class="nav-menu">
                    <li>
                        <a href="{{ url($rSlug . '/dashboard') }}" class="nav-link">
                            <i class="fa-solid fa-table-cells-large"></i>
                            <span class="nav-title">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(Auth::user()->role_slug . '/addadmin') }}" class="nav-link">
                            <i class="fa-solid fa-user-shield"></i>
                            <span class="nav-title">Admin</span>
                        </a>
                    </li>
                    <li>
                        <!-- Active: Branches -->
                        <a href="{{ url(Auth::user()->role_slug . '/branches') }}" class="nav-link active">
                            <i class="fa-solid fa-building"></i>
                            <span class="nav-title">Branches</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(Auth::user()->role_slug . '/branch-managers') }}" class="nav-link">
                            <i class="fa-solid fa-user-tie"></i>
                            <span class="nav-title">Branch Managers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(Auth::user()->role_slug . '/roles') }}" class="nav-link">
                            <i class="fa-solid fa-user-shield"></i>
                            <span class="nav-title">Roles & Permissions</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url(Auth::user()->role_slug . '/admin-permission') }}" class="nav-link">
                            <i class="fa-solid fa-shield-halved"></i>
                            <span class="nav-title">Admin Permissions</span>
                        </a>
                    </li>
                </ul>
            @endif
        </div>

        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="nav-title">Log Out</span>
                </button>
            </form>
        </div>
    </aside>
    @endif

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        @if($isLabFacility)
            @include('partials.topbar')
        @else
        <header class="topbar">
            <div>
                <button class="toggle-btn" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
            </div>
            <div class="topbar-actions">
                @if($isSuperAdmin)
                    <!-- Notification Bell -->
                    <button class="icon-circle-btn" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge-dot-red"></span>
                    </button>

                    <!-- Messages Bubble -->
                    <button class="icon-circle-btn" title="Messages">
                        <i class="fa-regular fa-comment-dots"></i>
                    </button>

                    <!-- Profile Badge SA with Dropdown -->
                    <div class="user-dropdown-wrap" id="userDropdownWrap">
                        <div class="user-profile-btn" id="userProfileBtn" onclick="toggleProfileDropdown(event)" title="Account Menu">
                            <div class="user-avatar-circle" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                                SA
                            </div>
                            <i class="fa-solid fa-chevron-down" style="font-size: 11px; color: #94a3b8;"></i>
                        </div>

                        <!-- Dropdown Content: Super Admin & Logout -->
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <div class="dropdown-user-header">
                                <div class="dropdown-avatar-circle">SA</div>
                                <div class="dropdown-user-details">
                                    <span class="dropdown-role-name">Super Admin</span>
                                    <span class="dropdown-role-sub">{{ Auth::user()->email ?? 'admin@gmail.com' }}</span>
                                </div>
                            </div>

                            <div class="dropdown-divider"></div>

                            <button type="button" class="dropdown-item-btn" onclick="triggerLogout()">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </div>
                    </div>
                @else
                    <!-- Facility / Lab Admin Topbar -->
                    <button class="icon-circle-btn" title="Notifications">
                        <i class="fa-regular fa-bell"></i>
                        <span class="badge-dot-red"></span>
                    </button>

                    <button class="icon-circle-btn" title="Messages">
                        <i class="fa-regular fa-comment-dots"></i>
                    </button>

                    <div class="user-dropdown-wrap" id="userDropdownWrap">
                        <div class="user-profile-btn" id="userProfileBtn" onclick="toggleProfileDropdown(event)" title="{{ Auth::user()->name }} ({{ Auth::user()->display_role }})">
                            <div class="user-avatar-circle">
                                {{ Auth::user()->initials }}
                            </div>
                            <i class="fa-solid fa-chevron-down chevron-icon" style="font-size: 10px; color: #94a3b8; margin-left: 2px;"></i>
                        </div>

                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown-menu" id="profileDropdownMenu">
                            <!-- Option 1: User / Facility Info -->
                            <div class="dropdown-item-header" style="{{ Auth::user()->is_branch_manager ? 'background: #ecfdf5; border-color: #a7f3d0;' : '' }}">
                                <div style="display: flex; align-items: flex-start; gap: 10px; width: 100%;">
                                    <div style="width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #059669 0%, #047857 100%); color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 13px; flex-shrink: 0;">
                                        {{ Auth::user()->initials }}
                                    </div>
                                    <div style="display: flex; flex-direction: column; text-align: left; gap: 2px; flex: 1;">
                                        <span style="font-size: 13.5px; font-weight: 700; color: #0f172a; line-height: 1.2;">{{ Auth::user()->name }}</span>
                                        <span style="font-size: 11.5px; font-weight: 600; color: #059669;">{{ Auth::user()->display_role }}</span>
                                        @if(Auth::user()->branch_name)
                                            <div style="margin-top: 5px; display: inline-flex; align-items: center; gap: 4px; background: #ffffff; border: 1px solid #6ee7b7; padding: 2px 7px; border-radius: 6px; font-size: 11px; font-weight: 700; color: #047857; width: fit-content;" title="Branch Code: {{ Auth::user()->branch_short }}">
                                                <i class="fa-solid fa-code-branch" style="font-size: 9px; color: #059669;"></i>
                                                <span>{{ Auth::user()->branch_name }}</span>
                                                @if(Auth::user()->branch_short && Auth::user()->branch_short !== Auth::user()->branch_name)
                                                    <span style="font-size: 9.5px; opacity: 0.8; font-weight: 600;">({{ Auth::user()->branch_short }})</span>
                                                @endif
                                            </div>
                                        @endif
                                        @if(Auth::user()->hospital_name)
                                            <span style="font-size: 10.5px; color: #64748b; margin-top: 3px;">
                                                <i class="fa-solid fa-hospital" style="margin-right: 3px; font-size: 9.5px;"></i>{{ Auth::user()->hospital_name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Option 2: My Profile -->
                            <a href="{{ url(Auth::user()->role_slug . '/profile') }}" class="dropdown-link-item">
                                <i class="fa-regular fa-user"></i>
                                <span>My Profile</span>
                            </a>

                            <!-- Option 3: Logout -->
                            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="dropdown-link-item logout-btn-item">
                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </header>
        @endif

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
                    <h1>{{ Auth::user()->role_slug === 'laboratory' ? 'Laboratory Branches & Centers' : 'Hospital Branches' }}</h1>
                    <p>{{ Auth::user()->role_slug === 'laboratory' ? 'Register, organize, and link collection centers and branches for ' . ($hospital->name ?? 'your laboratory') . '.' : 'Register, organize, and manage branch centers for your medical network.' }}</p>
                </div>

                @if($isMultiBranchEnabled)
                    <button class="btn-primary-add" onclick="openAddBranchModal()" style="{{ Auth::user()->role_slug === 'laboratory' ? 'background: #059669;' : '' }}">
                        <i class="fa-solid fa-plus"></i>
                        <span>{{ Auth::user()->role_slug === 'laboratory' ? 'Add Lab Branch' : 'Add Branch' }}</span>
                    </button>
                @endif
            </div>

            <!-- Permission Check Condition -->
            @if(!$isMultiBranchEnabled)
                <div class="locked-banner-card">
                    <div class="locked-icon-box">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <h3>Multi-Branch Feature is Disabled</h3>
                    <p>
                        @if(Auth::user()->role_slug === 'laboratory')
                            Your laboratory facility ({{ $hospital->name ?? 'Diagnostic Lab' }}) currently does not have multi-branch authorization enabled. 
                            Please contact the Super Admin to activate the <b>Multi-Branch System</b> toggle in Admin Permissions.
                        @else
                            Your medical facility currently does not have multi-branch authorization enabled. 
                            To add branches ("apna branch jod ke") and assign branch managers, please activate the <b>Multi-Branch System</b> toggle in Admin Permissions.
                        @endif
                    </p>
                    @if(Auth::user()->role_slug !== 'laboratory')
                        <a href="{{ url(Auth::user()->role_slug . '/admin-permission') }}" class="btn-unlock-perm">
                            <i class="fa-solid fa-shield-halved"></i> Go to Admin Permissions
                        </a>
                    @endif
                </div>
            @else
                <!-- TABLE CARD -->
                <div class="table-card">
                    <div class="card-topbar">
                        <div class="card-title-badge">
                            <h2>All Active Branches</h2>
                            <span class="count-bubble" id="countBubble">{{ count($branches) }}</span>
                        </div>

                        <div class="search-box-wrap">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" class="search-box-input" placeholder="Search branch name, code, city..." onkeyup="filterBranches(this.value)">
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>BRANCH</th>
                                    <th>PARENT FACILITY</th>
                                    <th>CITY & LOCATION</th>
                                    <th>CONTACT</th>
                                    <th>MANAGERS</th>
                                    <th>STATUS</th>
                                    <th style="text-align: right;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($branches as $index => $branch)
                                    <tr class="branch-row" data-search="{{ strtolower($branch->name . ' ' . $branch->branch_code . ' ' . $branch->city) }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="branch-cell">
                                                <div class="branch-avatar"><i class="fa-solid fa-building"></i></div>
                                                <div>
                                                    <b style="font-size: 13.5px; color: #0f172a;">{{ $branch->name }}</b>
                                                    <div style="font-size: 11px; color: #64748b;">{{ $branch->branch_code }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span style="font-weight: 600;">{{ $branch->hospital->name ?? 'Primary Facility' }}</span>
                                        </td>
                                        <td>
                                            <div><i class="fa-solid fa-location-dot" style="font-size: 11px; margin-right: 4px; color: #94a3b8;"></i> {{ $branch->city ?? 'Not set' }}</div>
                                            <small style="color: #64748b; font-size: 11px;">{{ Str::limit($branch->address, 30) }}</small>
                                        </td>
                                        <td>
                                            <div>{{ $branch->phone ?? '-' }}</div>
                                            <small style="color: #64748b;">{{ $branch->email ?? '-' }}</small>
                                        </td>
                                        <td>
                                            <span style="background: #eef2ff; color: #4338ca; padding: 2px 8px; border-radius: 4px; font-weight: 700; font-size: 11.5px;">
                                                <i class="fa-solid fa-user-tie"></i> {{ $branch->managers->count() }} Managers
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge-status {{ strtolower($branch->status) }}">{{ $branch->status }}</span>
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btns" style="justify-content: flex-end;">
                                                <button class="btn-action-icon" onclick='openEditBranchModal(@json($branch))' title="Edit">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form action="{{ url(Auth::user()->role_slug . '/branches/' . $branch->id) }}" method="POST" onsubmit="return confirm('Delete this branch?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-icon delete" title="Delete">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" style="text-align: center; padding: 40px; color: #94a3b8;">
                                            No branches added yet. Click <b>+ Add Branch</b> to link your first branch.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </main>
    </div>

    <!-- MODAL: ADD BRANCH -->
    <div class="modal-backdrop" id="addBranchModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add New Branch ("Apna Branch Jodein")</h3>
                <button class="close-x-btn" onclick="closeAddBranchModal()">&times;</button>
            </div>
            <form action="{{ url(Auth::user()->role_slug . '/branches') }}" method="POST">
                @csrf
                <div class="modal-content-pad">
                    <div class="grid-2col">
                        <div class="field-item" style="grid-column: span 2;">
                            <label>Branch Name</label>
                            <input type="text" name="name" class="field-input" placeholder="e.g. South Extension Branch" required>
                        </div>
                        <div class="field-item">
                            <label>Branch Code</label>
                            <input type="text" name="branch_code" class="field-input" placeholder="e.g. BR-01 (leave blank for auto)">
                        </div>
                        @if($isSuperAdmin)
                            <div class="field-item">
                                <label>Facility / Hospital</label>
                                <select name="hospital_id" class="field-input">
                                    @foreach($hospitals as $hosp)
                                        <option value="{{ $hosp->id }}" {{ ($hospital && $hospital->id == $hosp->id) ? 'selected' : '' }}>{{ $hosp->name }} ({{ $hosp->medical_type ?? 'Facility' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="field-item">
                                <label>Facility (Apna Facility)</label>
                                <input type="hidden" name="hospital_id" value="{{ $hospital->id ?? '' }}">
                                <div style="height: 42px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; display: flex; align-items: center; padding: 0 14px; font-weight: 700; color: #0f172a; font-size: 13.5px; gap: 8px;">
                                    <i class="fa-solid fa-flask-vial" style="color: #059669;"></i>
                                    <span>{{ $hospital->name ?? 'My Facility' }}</span>
                                    <span style="margin-left: auto; font-size: 11px; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-weight: 700;">{{ $hospital->medical_type ?? 'Laboratory' }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="field-item">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="field-input" placeholder="+91 98765 11111">
                        </div>
                        <div class="field-item">
                            <label>Email Address</label>
                            <input type="email" name="email" class="field-input" placeholder="branch@hospital.com">
                        </div>
                        <div class="field-item">
                            <label>City / Location</label>
                            <input type="text" name="city" class="field-input" placeholder="e.g. New Delhi">
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="field-item" style="grid-column: span 2;">
                            <label>Address Details</label>
                            <textarea name="address" class="field-input" style="height: 60px; padding: 10px;" placeholder="Full street address..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddBranchModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Create Branch</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT BRANCH -->
    <div class="modal-backdrop" id="editBranchModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Branch</h3>
                <button class="close-x-btn" onclick="closeEditBranchModal()">&times;</button>
            </div>
            <form id="editBranchForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content-pad">
                    <div class="grid-2col">
                        <div class="field-item" style="grid-column: span 2;">
                            <label>Branch Name</label>
                            <input type="text" name="name" id="edit_branch_name" class="field-input" required>
                        </div>
                        <div class="field-item">
                            <label>Branch Code</label>
                            <input type="text" name="branch_code" id="edit_branch_code" class="field-input">
                        </div>
                        @if($isSuperAdmin)
                            <div class="field-item">
                                <label>Facility / Hospital</label>
                                <select name="hospital_id" id="edit_branch_hosp_id" class="field-input">
                                    @foreach($hospitals as $hosp)
                                        <option value="{{ $hosp->id }}">{{ $hosp->name }} ({{ $hosp->medical_type ?? 'Facility' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="field-item">
                                <label>Facility (Apna Facility)</label>
                                <input type="hidden" name="hospital_id" id="edit_branch_hosp_id" value="{{ $hospital->id ?? '' }}">
                                <div style="height: 42px; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; display: flex; align-items: center; padding: 0 14px; font-weight: 700; color: #0f172a; font-size: 13.5px; gap: 8px;">
                                    <i class="fa-solid fa-flask-vial" style="color: #059669;"></i>
                                    <span>{{ $hospital->name ?? 'My Facility' }}</span>
                                    <span style="margin-left: auto; font-size: 11px; background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-weight: 700;">{{ $hospital->medical_type ?? 'Laboratory' }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="field-item">
                            <label>Phone Number</label>
                            <input type="text" name="phone" id="edit_branch_phone" class="field-input">
                        </div>
                        <div class="field-item">
                            <label>Email Address</label>
                            <input type="email" name="email" id="edit_branch_email" class="field-input">
                        </div>
                        <div class="field-item">
                            <label>City / Location</label>
                            <input type="text" name="city" id="edit_branch_city" class="field-input">
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" id="edit_branch_status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="field-item" style="grid-column: span 2;">
                            <label>Address Details</label>
                            <textarea name="address" id="edit_branch_address" class="field-input" style="height: 60px; padding: 10px;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditBranchModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Update Branch</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }

        function filterBranches(term) {
            const query = (term || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.branch-row');
            let count = 0;
            rows.forEach(r => {
                const text = r.getAttribute('data-search') || '';
                if (text.includes(query)) {
                    r.style.display = '';
                    count++;
                } else {
                    r.style.display = 'none';
                }
            });
            const bubble = document.getElementById('countBubble');
            if (bubble) bubble.innerText = count;
        }

        function openAddBranchModal() { document.getElementById('addBranchModal').style.display = 'flex'; }
        function closeAddBranchModal() { document.getElementById('addBranchModal').style.display = 'none'; }

        function openEditBranchModal(branch) {
            const form = document.getElementById('editBranchForm');
            form.action = `{{ url(Auth::user()->role_slug . '/branches') }}/${branch.id}`;
            document.getElementById('edit_branch_name').value = branch.name || '';
            document.getElementById('edit_branch_code').value = branch.branch_code || '';
            document.getElementById('edit_branch_hosp_id').value = branch.hospital_id || '';
            document.getElementById('edit_branch_phone').value = branch.phone || '';
            document.getElementById('edit_branch_email').value = branch.email || '';
            document.getElementById('edit_branch_city').value = branch.city || '';
            document.getElementById('edit_branch_address').value = branch.address || '';
            document.getElementById('edit_branch_status').value = branch.status || 'Active';
            document.getElementById('editBranchModal').style.display = 'flex';
        }
        function closeEditBranchModal() { document.getElementById('editBranchModal').style.display = 'none'; }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (!menu) return;
            const isOpen = menu.classList.contains('show');
            if (isOpen) {
                menu.classList.remove('show');
                if (btn) btn.classList.remove('active');
            } else {
                menu.classList.add('show');
                if (btn) btn.classList.add('active');
            }
        }

        function toggleMasterSubmenu(element) {
            const parent = element.closest('.nav-item-dropdown');
            const submenu = parent.querySelector('.nav-submenu');
            if (!submenu) return;
            if (submenu.style.display === 'none' || !submenu.style.display) {
                submenu.style.display = 'block';
                parent.classList.add('open');
            } else {
                submenu.style.display = 'none';
                parent.classList.remove('open');
            }
        }

        function triggerLogout() {
            const form = document.getElementById('logoutForm');
            if (form) form.submit();
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
    </script>
    @include('partials.validation_popup')
</body>
</html>
