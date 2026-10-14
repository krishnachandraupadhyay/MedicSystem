<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles & Permissions - {{ $hospital->name ?? 'Laboratory System' }}</title>
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
            border-bottom: 1px solid #eef2f7;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
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
        .toggle-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
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
            background: #059669; color: #ffffff; border: none; padding: 11px 22px; border-radius: 10px;
            font-size: 13.5px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.28); transition: all 0.2s;
        }
        .btn-primary-add:hover { background: #047857; transform: translateY(-1px); }

        .toast-msg {
            background: #10b981; color: #ffffff; padding: 12px 18px; border-radius: 10px; font-size: 13.5px;
            font-weight: 600; display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;
        }
        .toast-error { background: #ef4444; }

        /* STATS OVERVIEW CARDS */
        .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 18px; margin-bottom: 24px;
        }
        @media(max-width: 900px) { .stats-grid { grid-template-columns: 1fr; } }
        .stat-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 14px; padding: 20px 22px;
            display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 6px rgba(0,0,0,0.02);
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px;
        }
        .stat-val { font-size: 22px; font-weight: 800; color: #0f172a; }
        .stat-lbl { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 2px; }

        /* TABLE CARD */
        .table-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 16px; box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03); overflow: hidden;
        }
        .card-topbar {
            padding: 20px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;
        }
        .card-title-badge { display: flex; align-items: center; gap: 10px; }
        .card-title-badge h2 { font-size: 16px; font-weight: 700; color: #1e293b; }
        .count-bubble { background: #ecfdf5; color: #059669; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

        .search-box-wrap { position: relative; min-width: 260px; }
        .search-box-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        .search-box-input {
            width: 100%; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px 0 38px; font-size: 13.5px; color: #1e293b; outline: none;
        }
        .search-box-input:focus { background: #ffffff; border-color: #059669; }

        .table-wrap { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th {
            background: #f8fafc; color: #475569; font-size: 11.5px; font-weight: 700; letter-spacing: 0.6px;
            text-transform: uppercase; padding: 12px 18px; border-bottom: 1px solid #eef2f7;
        }
        .data-table td { padding: 14px 18px; border-bottom: 1px solid #f1f5f9; font-size: 13px; color: #334155; vertical-align: middle; }

        .role-avatar {
            width: 38px; height: 38px; border-radius: 10px; background: #ecfdf5; color: #059669; display: flex;
            align-items: center; justify-content: center; font-size: 16px;
        }
        .role-cell { display: flex; align-items: center; gap: 12px; }

        /* PERMISSION TAGS */
        .perm-tag-cluster { display: flex; flex-wrap: wrap; gap: 6px; max-width: 520px; }
        .perm-chip {
            font-size: 11px; font-weight: 600; padding: 3px 8px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;
            background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;
        }
        .perm-chip.staff { background: #eef2ff; color: #4338ca; border-color: #c7d2fe; }
        .perm-chip.attendance { background: #ecfdf5; color: #047857; border-color: #a7f3d0; }
        .perm-chip.branches { background: #fffbeb; color: #b45309; border-color: #fde68a; }
        .perm-chip.tests { background: #ecfeff; color: #0e7490; border-color: #a5f3fc; }
        .perm-chip.reports { background: #faf5ff; color: #7e22ce; border-color: #e9d5ff; }

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
            position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(4px);
            display: none; align-items: center; justify-content: center; z-index: 1000; padding: 20px;
            overflow-y: auto;
        }
        .modal-box {
            background: #ffffff; width: 100%; max-width: 860px; max-height: 90vh; height: 90vh; border-radius: 18px; overflow: hidden;
            display: flex; flex-direction: column; box-shadow: 0 25px 30px -5px rgba(0, 0, 0, 0.2); animation: zoomIn 0.2s ease;
            margin: auto;
        }
        @keyframes zoomIn { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .modal-box form {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        .modal-head {
            padding: 20px 26px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
            background: #ffffff;
        }
        .modal-head h3 { font-size: 17.5px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; }
        .close-x-btn { background: none; border: none; font-size: 24px; color: #94a3b8; cursor: pointer; line-height: 1; }

        .modal-scroll-area {
            padding: 24px 26px;
            overflow-y: auto;
            flex: 1;
            min-height: 0;
            scrollbar-width: thin;
            scrollbar-color: #cbd5e1 #f8fafc;
            -webkit-overflow-scrolling: touch;
        }
        .modal-scroll-area::-webkit-scrollbar {
            width: 8px;
        }
        .modal-scroll-area::-webkit-scrollbar-track {
            background: #f8fafc;
        }
        .modal-scroll-area::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        .modal-scroll-area::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 24px; }
        @media(max-width: 600px) { .grid-2col { grid-template-columns: 1fr; } }
        .field-item { display: flex; flex-direction: column; gap: 6px; }
        .field-item label { font-size: 12px; font-weight: 700; color: #475569; }
        .field-input {
            width: 100%; height: 42px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px; font-size: 13.5px; color: #1e293b; outline: none; transition: all 0.2s;
        }
        .field-input:focus { background: #ffffff; border-color: #059669; box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.1); }

        /* PERMISSION MODULE CARDS */
        .permissions-section-header {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; padding-bottom: 10px; border-bottom: 1.5px solid #f1f5f9;
        }
        .permissions-section-header h4 { font-size: 15px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 8px; }
        .perm-quick-actions { display: flex; gap: 8px; }
        .btn-quick-toggle {
            background: #f1f5f9; border: 1px solid #e2e8f0; color: #475569; padding: 5px 12px; border-radius: 6px;
            font-size: 11.5px; font-weight: 700; cursor: pointer; transition: all 0.2s;
        }
        .btn-quick-toggle:hover { background: #e2e8f0; color: #1e293b; }

        .perm-modules-grid { display: flex; flex-direction: column; gap: 16px; }

        .module-card {
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 20px; transition: all 0.2s;
        }
        .module-card:hover { border-color: #cbd5e1; background: #fdfdfd; }
        .module-card-head {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 10px; border-bottom: 1px dashed #e2e8f0;
        }
        .module-title-box { display: flex; align-items: center; gap: 10px; }
        .module-icon-wrap {
            width: 34px; height: 34px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 15px;
            color: #ffffff;
        }
        .module-info h5 { font-size: 14px; font-weight: 800; color: #1e293b; }
        .module-info p { font-size: 11px; color: #64748b; margin-top: 1px; }

        .module-actions-row {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 12px;
        }

        .action-toggle-item {
            background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 10px 14px;
            display: flex; align-items: center; justify-content: space-between; gap: 10px; transition: all 0.2s;
        }
        .action-toggle-item:hover { border-color: #cbd5e1; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .action-toggle-item.checked { background: #f0fdf4; border-color: #86efac; }
        .action-name { font-size: 12.5px; font-weight: 700; color: #334155; }

        /* TOGGLE SWITCH (DEFAULT OFF) */
        .switch-wrap {
            position: relative; display: inline-block; width: 38px; height: 22px; flex-shrink: 0;
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

        .modal-action-bar {
            padding: 16px 26px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 12px; flex-shrink: 0;
        }
        .btn-modal-cancel { padding: 10px 20px; border-radius: 8px; border: 1px solid #cbd5e1; background: #ffffff; cursor: pointer; font-weight: 600; font-size: 13.5px; }
        .btn-modal-submit {
            padding: 10px 24px; border-radius: 8px; border: none; background: #059669; color: #ffffff; cursor: pointer; font-weight: 700; font-size: 13.5px;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.3); transition: all 0.2s;
        }
        .btn-modal-submit:hover { background: #047857; }
    </style>
</head>
<body>

@php
    $user = Auth::user();
    $rSlug = $user->role_slug ?? 'laboratory';
    $isSuperAdmin = ($user && ($user->role === 'Super Admin' || $rSlug === 'superadmin'));
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
                    <h1>Facility Roles Management</h1>
                    <p>Manage laboratory staff roles and account privileges. Module permissions are configured separately on the Permissions page.</p>
                </div>

                <div style="display: flex; gap: 10px; align-items: center;">
                    <a href="{{ url($rSlug . '/permissions') }}" style="background: #ffffff; border: 1px solid #cbd5e1; padding: 10px 18px; border-radius: 10px; color: #334155; font-weight: 700; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-shield-halved" style="color: #059669;"></i>
                        <span>Configure Permissions</span>
                    </a>
                    @if(!Auth::user()->is_branch_manager)
                        <button class="btn-primary-add" onclick="openAddRoleModal()">
                            <i class="fa-solid fa-plus"></i>
                            <span>Create Role</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- TABLE CARD -->
            <div class="table-card">
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Facility Roles</h2>
                        <span class="count-bubble" id="countBubble">{{ count($roles) }}</span>
                    </div>

                    <div class="search-box-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-box-input" placeholder="Search role name, description..." onkeyup="filterRoles(this.value)">
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;">#</th>
                                <th>ROLE NAME</th>
                                <th>DESCRIPTION</th>
                                <th>PERMISSIONS STATUS</th>
                                <th>STATUS</th>
                                <th style="text-align: right;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                                <tr class="role-row" data-search="{{ strtolower($role->name . ' ' . $role->slug . ' ' . $role->description) }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="role-cell">
                                            <div class="role-avatar"><i class="fa-solid fa-user-gear"></i></div>
                                            <div>
                                                <b style="font-size: 13.5px; color: #0f172a;">{{ $role->name }}</b>
                                                <div style="font-size: 11px; color: #64748b;">{{ $role->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="color: #64748b; font-size: 12.5px;">{{ $role->description ? Str::limit($role->description, 50) : 'No description provided' }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ url($rSlug . '/permissions?role_id=' . $role->id) }}" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px; background: #ecfdf5; color: #047857; padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 12px; border: 1px solid #a7f3d0;" title="Configure Permissions for {{ $role->name }}">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <span>{{ $role->active_permissions_count }} Active Permissions</span>
                                            <i class="fa-solid fa-arrow-right" style="font-size: 10px; margin-left: 2px;"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge-status {{ strtolower($role->status) }}">{{ $role->status }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="action-btns" style="justify-content: flex-end;">
                                            <a href="{{ url($rSlug . '/permissions?role_id=' . $role->id) }}" class="btn-action-icon" title="Configure Permissions" style="color: #059669; text-decoration: none;">
                                                <i class="fa-solid fa-shield-halved"></i>
                                            </a>
                                            @if(!Auth::user()->is_branch_manager)
                                                <button class="btn-action-icon" onclick='openEditRoleModal(@json($role))' title="Edit Role Details">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <form action="{{ url(Auth::user()->role_slug . '/roles/' . $role->id) }}" method="POST" onsubmit="return confirm('Delete role {{ $role->name }}?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-icon delete" title="Delete Role">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                        <i class="fa-solid fa-user-shield" style="font-size: 38px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                        No roles have been created yet.<br>
                                        Click <b>Create Role</b> to add your first role!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    @if(!Auth::user()->is_branch_manager)
    <!-- MODAL: CREATE ROLE -->
    <div class="modal-backdrop" id="addRoleModal">
        <div class="modal-box" style="max-width: 520px;">
            <div class="modal-head">
                <h3><i class="fa-solid fa-user-plus" style="color: #059669;"></i> Create New Role</h3>
                <button type="button" class="close-x-btn" onclick="closeAddRoleModal()">&times;</button>
            </div>
            <form action="{{ url(Auth::user()->role_slug . '/roles') }}" method="POST">
                @csrf
                <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <div class="field-item">
                        <label>Role Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" class="field-input" placeholder="e.g. Branch Manager, Lab Technician" required>
                    </div>

                    <div class="field-item">
                        <label>Status</label>
                        <select name="status" class="field-input">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
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
                        <input type="hidden" name="hospital_id" value="{{ $hospital->id ?? '' }}">
                    @endif

                    <div class="field-item">
                        <label>Role Description</label>
                        <textarea name="description" class="field-input" style="height: 70px; padding: 10px;" placeholder="Role duties, access privileges summary..."></textarea>
                    </div>

                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 14px; border-radius: 10px; font-size: 12px; color: #166534; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-circle-info" style="font-size: 15px; color: #059669;"></i>
                        <span>Role create karne ke baad iske permissions aap <b>Permissions</b> page se manage kar sakte hain.</span>
                    </div>
                </div>

                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddRoleModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Create Role</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT ROLE -->
    <div class="modal-backdrop" id="editRoleModal">
        <div class="modal-box" style="max-width: 520px;">
            <div class="modal-head">
                <h3><i class="fa-solid fa-pen-to-square" style="color: #059669;"></i> Edit Role Details</h3>
                <button type="button" class="close-x-btn" onclick="closeEditRoleModal()">&times;</button>
            </div>
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')
                <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                    <div class="field-item">
                        <label>Role Name <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" id="edit_role_name" class="field-input" required>
                    </div>

                    <div class="field-item">
                        <label>Status</label>
                        <select name="status" id="edit_role_status" class="field-input">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>

                    <div class="field-item">
                        <label>Role Description</label>
                        <textarea name="description" id="edit_role_description" class="field-input" style="height: 70px; padding: 10px;" placeholder="Role duties, access privileges summary..."></textarea>
                    </div>
                </div>

                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditRoleModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Update Role</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }

        function filterRoles(term) {
            const query = (term || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.role-row');
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

        @if(!Auth::user()->is_branch_manager)
        // Add Modal helpers
        function openAddRoleModal() {
            document.getElementById('addRoleModal').style.display = 'flex';
        }
        function closeAddRoleModal() {
            document.getElementById('addRoleModal').style.display = 'none';
        }

        // Edit Modal helpers
        function openEditRoleModal(role) {
            const form = document.getElementById('editRoleForm');
            form.action = `{{ url(Auth::user()->role_slug . '/roles') }}/${role.id}`;
            document.getElementById('edit_role_name').value = role.name || '';
            document.getElementById('edit_role_status').value = role.status || 'Active';
            document.getElementById('edit_role_description').value = role.description || '';
            document.getElementById('editRoleModal').style.display = 'flex';
        }
        function closeEditRoleModal() {
            document.getElementById('editRoleModal').style.display = 'none';
        }

        window.addEventListener('click', function(e) {
            const addM = document.getElementById('addRoleModal');
            const editM = document.getElementById('editRoleModal');
            if (e.target === addM) closeAddRoleModal();
            if (e.target === editM) closeEditRoleModal();
        });
        @endif

        // Topbar Profile Dropdown
        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const wrap = document.getElementById('userDropdownWrap');
            const menu = document.getElementById('profileDropdownMenu');
            if (menu) {
                const isOpen = menu.classList.contains('show');
                menu.classList.toggle('show');
                if (wrap) wrap.classList.toggle('open', !isOpen);
            }
        }

        document.addEventListener('click', function(e) {
            const wrap = document.getElementById('userDropdownWrap');
            const menu = document.getElementById('profileDropdownMenu');
            if (menu && !e.target.closest('#userDropdownWrap')) {
                menu.classList.remove('show');
                if (wrap) wrap.classList.remove('open');
            }
        });
    </script>
    @include('partials.validation_popup')
</body>
</html>
