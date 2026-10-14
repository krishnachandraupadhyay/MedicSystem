<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnostic Tests & Fees Catalog - {{ $hospital->name ?? 'Diagnostic Lab' }}</title>
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
            --text-heading: #111827;
            --text-muted: #8c97a8;
            --border-color: #f1f4f9;
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
            position: sticky;
            top: 0;
            z-index: 50;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: hidden;
        }

        .sidebar.collapsed {
            width: 78px;
        }

        .sidebar.collapsed .brand-info,
        .sidebar.collapsed .menu-heading,
        .sidebar.collapsed .nav-title {
            display: none;
        }

        .sidebar.collapsed .brand-block {
            justify-content: center;
            padding: 24px 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
            margin: 4px 10px;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
            font-size: 18px;
        }

        .brand-block {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 24px 22px;
            flex-shrink: 0;
        }

        .brand-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 19px;
            box-shadow: 0 8px 18px rgba(99, 102, 241, 0.35);
            flex-shrink: 0;
        }

        .brand-info h2 {
            font-size: 14.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .brand-info p {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 160px;
        }

        .sidebar-scroll-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
        }

        .sidebar-scroll-content::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .menu-heading {
            padding: 16px 24px 8px;
            font-size: 10px;
            font-weight: 700;
            color: #4b586e;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            flex-shrink: 0;
        }

        .nav-menu {
            list-style: none;
            padding: 4px 12px 16px;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 14px;
            border-radius: 9px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: #ffffff;
        }

        .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-text-active);
            position: relative;
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 6px;
            bottom: 6px;
            width: 4px;
            background: #6366f1;
            border-radius: 0 4px 4px 0;
        }

        .nav-link i {
            width: 22px;
            font-size: 16px;
            margin-right: 12px;
            flex-shrink: 0;
            text-align: center;
        }

        .sidebar-footer {
            padding: 16px 12px 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            padding: 11px 14px;
            border-radius: 9px;
            color: #94a3b8;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: rgba(239, 68, 68, 0.12);
            color: #ef4444;
        }

        .btn-logout i {
            width: 22px;
            font-size: 16px;
            margin-right: 12px;
        }

        /* ------------------ MAIN CONTENT ------------------ */
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
            border-bottom: 1px solid var(--border-color);
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

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 4px 12px 4px 4px;
            border-radius: 30px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .user-profile-btn:hover {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
            box-shadow: 0 2px 6px rgba(5, 150, 105, 0.25);
            flex-shrink: 0;
        }

        .user-dropdown-wrap {
            position: relative;
        }

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
            display: block;
            animation: dropdownFade 0.18s ease-out;
        }

        @keyframes dropdownFade {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 4px 0;
        }

        .dropdown-item-btn {
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
        }

        .dropdown-item-btn:hover {
            background: #f1f5f9;
            color: #4f46e5;
        }

        .dropdown-item-btn.logout-btn-item {
            color: #ef4444;
        }

        .dropdown-item-btn.logout-btn-item:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .content-area {
            padding: 32px;
            flex: 1;
        }

        /* Alert Toasts */
        .toast-msg {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 600;
            animation: fadeIn 0.3s ease;
        }
        .toast-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .toast-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        /* Page Header */
        .page-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .page-heading h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.4px;
        }

        .page-heading p {
            font-size: 13.5px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-action-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
            color: #ffffff;
        }

        .btn-action-secondary {
            background: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 10px 16px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-action-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }

        .btn-sync-branches {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 18px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);
            transition: all 0.2s ease;
        }

        .btn-sync-branches:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.4);
        }

        /* STATS GRID */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
            border: 1px solid var(--border-color);
        }

        .stat-info span {
            font-size: 12.5px;
            color: #64748b;
            font-weight: 600;
        }

        .stat-info h3 {
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
            margin-top: 4px;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        .stat-icon.purple { background: #eef2ff; color: #4f46e5; }
        .stat-icon.green  { background: #ecfdf5; color: #059669; }
        .stat-icon.blue   { background: #eff6ff; color: #2563eb; }
        .stat-icon.amber  { background: #fffbeb; color: #d97706; }

        /* FILTER & CONTROL BAR */
        .filter-bar-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            border: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .filters-left {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .filter-select {
            padding: 8px 14px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
            background: #ffffff;
            outline: none;
            min-width: 170px;
        }

        .search-wrap {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 14px;
            width: 300px;
            max-width: 100%;
        }

        .search-wrap input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            margin-left: 8px;
        }

        /* TABLE CARD */
        .table-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-topbar {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
        }

        .card-topbar h2 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .badge-counter {
            font-size: 12px;
            font-weight: 700;
            background: #eef2ff;
            color: #4f46e5;
            padding: 2px 10px;
            border-radius: 12px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            padding: 14px 20px;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            background: #f8fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .data-table td {
            padding: 16px 20px;
            font-size: 13.5px;
            color: #1e293b;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .price-badge {
            font-weight: 800;
            color: #059669;
            font-size: 14px;
            background: #ecfdf5;
            padding: 4px 10px;
            border-radius: 8px;
            display: inline-block;
        }

        .branch-badge {
            font-size: 11.5px;
            font-weight: 600;
            padding: 3px 8px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .branch-badge.all {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 700;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .status-pill.active { background: #dcfce7; color: #166534; }
        .status-pill.inactive { background: #f1f5f9; color: #64748b; }

        .btn-icon-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 4px;
        }
        .btn-icon-action:hover {
            background: #f8fafc;
            color: #4f46e5;
            border-color: #cbd5e1;
        }
        .btn-icon-action.delete:hover {
            background: #fef2f2;
            color: #ef4444;
            border-color: #fecaca;
        }

        /* ------------------ MODALS ------------------ */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            padding: 16px;
        }

        .modal-box {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 650px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            animation: modalPop 0.2s ease-out;
        }

        @keyframes modalPop {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-head {
            padding: 22px 28px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-head h3 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: #94a3b8;
            cursor: pointer;
        }

        .modal-body {
            padding: 24px 28px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-group label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }

        .field-control {
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            outline: none;
            transition: all 0.2s;
            background: #ffffff;
            color: #0f172a;
        }

        .field-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .modal-foot {
            padding: 18px 28px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            border-radius: 0 0 20px 20px;
        }

        .btn-cancel {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            color: #475569;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            border: none;
            color: #ffffff;
            padding: 10px 22px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
        }

        /* Banner Box for 1-Click Sync */
        .sync-info-banner {
            background: #f0fdf4;
            border: 1px dashed #86efac;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .sync-info-text {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sync-icon-circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        @media (max-width: 900px) {
            .sidebar { position: fixed; left: -260px; }
            .sidebar.mobile-open { left: 0; }
            .content-area { padding: 20px 16px; }
            .form-grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@php
    $rSlug = Auth::user()->role_slug ?? 'laboratory';
@endphp

    @include('partials.laboratory_sidebar')

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        @include('partials.topbar')

        <main class="content-area">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="toast-msg toast-success">
                    <div><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}</div>
                    <button style="background:none; border:none; cursor:pointer;" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif
            @if(session('error'))
                <div class="toast-msg toast-error">
                    <div><i class="fa-solid fa-circle-exclamation" style="margin-right: 8px;"></i> {{ session('error') }}</div>
                    <button style="background:none; border:none; cursor:pointer;" onclick="this.parentElement.remove()">&times;</button>
                </div>
            @endif

            <!-- Page Header Row -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Diagnostic Lab Tests Catalog</h1>
                    <p>Configure test catalog, sample requirements, turnaround time and department mapping for <b>{{ $hospital->name ?? 'Diagnostic Lab' }}</b></p>
                </div>

                <div class="header-actions">
                    @if($canCreate)
                        <button type="button" class="btn-action-primary" onclick="openAddTestModal()">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Test</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- WORKFLOW STEPS BANNER -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span style="font-weight: 800; font-size: 13px; color: #1e293b;"><i class="fa-solid fa-diagram-project" style="color: #059669;"></i> Standard Workflow:</span>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748b; font-weight: 600; flex-wrap: wrap;">
                        <a href="{{ url($rSlug . '/test-categories') }}" style="text-decoration: none; background: #ecfdf5; color: #047857; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #a7f3d0;" title="Step 1: Test Category">Step 1: Category Create</a>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <span style="background: #eff6ff; color: #1d4ed8; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #bfdbfe;">Step 2: Test Create</span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <a href="{{ url($rSlug . '/test-fees') }}" style="text-decoration: none; background: #f0fdf4; color: #15803d; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #bbf7d0;" title="Step 3: Test Fees & Pricing">Step 3: Fees &amp; Pricing</a>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <span style="background: #fef3c7; color: #b45309; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #fde68a;">Step 4: Admin Verification</span>
                    </div>
                </div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">
                    <i class="fa-solid fa-shield-halved" style="color: #059669;"></i> Admin verification ensures branch-specific test accuracy.
                </div>
            </div>


            <!-- FILTER & SEARCH BAR -->
            <form action="{{ url($rSlug . '/tests') }}" method="GET" class="filter-bar-card">
                <div class="filters-left">
                    <!-- Filter by Branch -->
                    @if(!Auth::user()->is_branch_manager)
                    <select name="branch_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">🏢 All Lab Branches</option>
                        @foreach($branches as $b)
                            <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>
                                {{ $b->name }} ({{ $b->branch_code ?? 'BR' }})
                            </option>
                        @endforeach
                    </select>
                    @endif

                    <!-- Filter by Category -->
                    <select name="category_id" class="filter-select" onchange="this.form.submit()">
                        <option value="">🔬 All Categories</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ $selectedCategoryId == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Filter by Status -->
                    <select name="status" class="filter-select" onchange="this.form.submit()">
                        <option value="">⚡ All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending Verification" {{ request('status') == 'Pending Verification' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    @if((!Auth::user()->is_branch_manager && $selectedBranchId) || $selectedCategoryId || $search || request('status'))
                        <a href="{{ url($rSlug . '/tests') }}" style="font-size: 13px; color: #ef4444; font-weight: 700; text-decoration: none; padding: 6px 10px;">
                            <i class="fa-solid fa-xmark"></i> Reset Filters
                        </a>
                    @endif
                </div>

                <div class="search-wrap">
                    <i class="fa-solid fa-magnifying-glass" style="color: #94a3b8;"></i>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search test name, code, fees..." onchange="this.form.submit()">
                </div>
            </form>

            <!-- TESTS & PRICING TABLE CARD -->
            <div class="table-card">
                <div class="card-topbar">
                    <h2>
                        <span>Diagnostic Tests &amp; Fees</span>
                        <span class="badge-counter">{{ count($tests) }}</span>
                    </h2>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>TEST NAME &amp; CODE</th>
                                <th>CATEGORY</th>
                                <th>SPECIMEN / SAMPLE</th>
                                <th>METHOD / TECHNIQUE</th>
                                <th>TAT (DURATION)</th>
                                <th>BRANCH SCOPE</th>
                                <th>STATUS</th>
                                <th style="text-align: right; width: 140px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tests as $idx => $test)
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">{{ $idx + 1 }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $test->name }}</div>
                                        @if(!empty($test->code))
                                            <span style="font-size: 11px; color: #64748b; font-weight: 600;">Code: {{ $test->code }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #334155;">
                                            {{ $test->category->name ?? 'General Diagnostic' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #475569; font-weight: 500;">
                                            <i class="fa-solid fa-vial" style="color: #94a3b8; font-size: 11px; margin-right: 4px;"></i>
                                            {{ $test->sample_type ?? 'Blood / Specimen' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #334155; font-size: 12.5px; font-weight: 600;">
                                            {{ $test->method ?? 'Automated Analyzer' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span style="color: #64748b; font-size: 12.5px;">
                                            <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                                            {{ $test->turnaround_time ?? 'Same Day' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($test->branch)
                                            <span class="branch-badge">
                                                <i class="fa-solid fa-building"></i> {{ $test->branch->name }}
                                            </span>
                                        @else
                                            <span class="branch-badge all">
                                                <i class="fa-solid fa-building-user"></i> All Lab Branches
                                            </span>
                                        @endif
                                                                      <td>
                                        @if($test->status === 'Pending Verification')
                                            <span style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                                <i class="fa-solid fa-clock"></i> Pending Verification
                                            </span>
                                        @elseif(strtolower($test->status) === 'active')
                                            <span class="status-pill active">
                                                Active
                                            </span>
                                        @else
                                            <span class="status-pill inactive">
                                                {{ $test->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="text-align: right; white-space: nowrap;">
                                        @if($test->status === 'Pending Verification' && (Auth::user()->is_admin || in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])))
                                            <form action="{{ url($rSlug . '/tests/' . $test->id . '/verify') }}" method="POST" style="display:inline;" onsubmit="return confirm('Verify & approve test {{ $test->name }} for branch publication?')">
                                                @csrf
                                                <button type="submit" class="btn-icon-action" title="Verify &amp; Approve Test for Branch" style="color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0;">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($canEdit)
                                            <button type="button" class="btn-icon-action" title="Edit Test Details" onclick="openEditTestModal({{ json_encode($test) }})">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <a href="{{ url($rSlug . '/test-fees?search=' . urlencode($test->name)) }}" class="btn-icon-action" title="Manage / Set Test Fee" style="color: #059669; text-decoration: none;">
                                                <i class="fa-solid fa-indian-rupee-sign"></i>
                                            </a>
                                        @endif
                                        @if($canDelete)
                                            <form action="{{ url($rSlug . '/tests/' . $test->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete test {{ $test->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon-action delete" title="Delete Test">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" style="text-align: center; padding: 40px; color: #64748b;">
                                        <i class="fa-solid fa-flask-vial" style="font-size: 32px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                        No diagnostic tests registered yet. Click <b>+ Add Lab Test</b> to add your first test!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- ------------------ MODAL: ADD TEST ------------------ -->
    <div class="modal-backdrop" id="addTestModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add Diagnostic Lab Test</h3>
                <button type="button" class="close-btn" onclick="closeAddTestModal()">&times;</button>
            </div>
            <form action="{{ url($rSlug . '/tests') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="field-group" style="grid-column: span 2;">
                            <label>Test Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="field-control" placeholder="e.g. Complete Blood Count (CBC)" required>
                        </div>

                        <div class="field-group">
                            <label>Test Category <span style="color:#ef4444;">*</span></label>
                            <select name="test_category_id" id="add_test_category_id" class="field-control" required onchange="onCategorySelectChange(this, 'add_test_branch_id', 'add_sync_branches')">
                                <option value="" disabled selected>-- Select Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-branch-id="{{ $cat->branch_id ?? '' }}">
                                        {{ $cat->name }} @if($cat->branch)({{ $cat->branch->name }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field-group">
                            <label>Test Code</label>
                            <input type="text" name="code" class="field-control" placeholder="e.g. TST-CBC-01">
                        </div>

                        <div class="field-group">
                            <label>Specimen / Sample Type</label>
                            <input type="text" name="sample_type" class="field-control" placeholder="e.g. EDTA Whole Blood, Serum, Urine">
                        </div>

                        <div class="field-group">
                            <label>Turnaround Time (TAT)</label>
                            <input type="text" name="turnaround_time" class="field-control" placeholder="e.g. 6 Hours, Same Day, 24 Hours">
                        </div>

                        <div class="field-group">
                            <label>Method / Technique</label>
                            <input type="text" name="method" class="field-control" placeholder="e.g. Automated Cell Counter, ELISA">
                        </div>

                        <div class="field-group">
                            <label>Assign to Branch</label>
                            @if(Auth::user()->is_branch_manager && Auth::user()->branch)
                                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch->id }}">
                                <input type="text" class="field-control" value="🏢 {{ Auth::user()->branch_name }}" readonly style="background: #f1f5f9; cursor: not-allowed; font-weight: 600; color: #475569;">
                            @else
                                <select name="branch_id" id="add_test_branch_id" class="field-control">
                                    <option value="">🏢 All Lab Branches</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }} ({{ $b->branch_code ?? 'BR' }})</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="field-group" style="grid-column: span 2;">
                            <label>Status</label>
                            <select name="status" class="field-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <!-- Apply / Sync to all branches option checkbox -->
                        <div class="field-group" style="grid-column: span 2; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 16px; border-radius: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; color: #166534;">
                                <input type="checkbox" name="sync_all_branches" id="add_sync_branches" value="1" style="width: 18px; height: 18px; accent-color: #059669;" checked>
                                <span><b>Replicate to All Branches:</b> Make this test profile available across all lab branches.</span>
                            </label>
                        </div>

                        <div class="field-group" style="grid-column: span 2;">
                            <label>Description / Clinical Notes</label>
                            <textarea name="description" class="field-control" rows="2" placeholder="Clinical notes, specimen instructions..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeAddTestModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Save Lab Test</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ------------------ MODAL: EDIT TEST ------------------ -->
    <div class="modal-backdrop" id="editTestModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Diagnostic Lab Test</h3>
                <button type="button" class="close-btn" onclick="closeEditTestModal()">&times;</button>
            </div>
            <form id="editTestForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="field-group" style="grid-column: span 2;">
                            <label>Test Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" id="edit_test_name" class="field-control" required>
                        </div>

                        <div class="field-group">
                            <label>Test Category <span style="color:#ef4444;">*</span></label>
                            <select name="test_category_id" id="edit_test_category_id" class="field-control" required onchange="onCategorySelectChange(this, 'edit_test_branch_id', 'edit_sync_branches')">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" data-branch-id="{{ $cat->branch_id ?? '' }}">
                                        {{ $cat->name }} @if($cat->branch)({{ $cat->branch->name }})@endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field-group">
                            <label>Test Code</label>
                            <input type="text" name="code" id="edit_test_code" class="field-control">
                        </div>

                        <div class="field-group">
                            <label>Specimen / Sample Type</label>
                            <input type="text" name="sample_type" id="edit_test_sample_type" class="field-control">
                        </div>

                        <div class="field-group">
                            <label>Turnaround Time (TAT)</label>
                            <input type="text" name="turnaround_time" id="edit_test_tat" class="field-control">
                        </div>

                        <div class="field-group">
                            <label>Method / Technique</label>
                            <input type="text" name="method" id="edit_test_method" class="field-control">
                        </div>

                        <div class="field-group">
                            <label>Assign to Branch</label>
                            @if(Auth::user()->is_branch_manager && Auth::user()->branch)
                                <input type="hidden" name="branch_id" id="edit_test_branch_id" value="{{ Auth::user()->branch->id }}">
                                <input type="text" class="field-control" value="🏢 {{ Auth::user()->branch_name }}" readonly style="background: #f1f5f9; cursor: not-allowed; font-weight: 600; color: #475569;">
                            @else
                                <select name="branch_id" id="edit_test_branch_id" class="field-control">
                                    <option value="">🏢 All Lab Branches</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div class="field-group" style="grid-column: span 2;">
                            <label>Status</label>
                            <select name="status" id="edit_test_status" class="field-control" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="field-group" style="grid-column: span 2; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 12px 16px; border-radius: 10px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; margin: 0; color: #166534;">
                                <input type="checkbox" name="sync_all_branches" id="edit_sync_branches" value="1" style="width: 18px; height: 18px; accent-color: #059669;">
                                <span><b>Update on All Branches:</b> Replicate these updates across all lab branches.</span>
                            </label>
                        </div>

                        <div class="field-group" style="grid-column: span 2;">
                            <label>Description / Clinical Notes</label>
                            <textarea name="description" id="edit_test_description" class="field-control" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeEditTestModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Lab Test</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const m = document.getElementById('profileDropdownMenu');
            if (m) m.classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const m = document.getElementById('profileDropdownMenu');
            if (m && m.classList.contains('show')) m.classList.remove('show');
        });

        // Test Modal Handlers
        function openAddTestModal() {
            const modal = document.getElementById('addTestModal');
            if (modal) {
                modal.style.display = 'flex';
                const catSelect = document.getElementById('add_test_category_id');
                if (catSelect && catSelect.value) {
                    onCategorySelectChange(catSelect, 'add_test_branch_id', 'add_sync_branches');
                }
            }
        }

        function closeAddTestModal() {
            const modal = document.getElementById('addTestModal');
            if (modal) modal.style.display = 'none';
        }

        function openEditTestModal(test) {
            const form = document.getElementById('editTestForm');
            if (form) form.action = `{{ url($rSlug . '/tests') }}/${test.id}`;

            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (el) el.value = (val !== undefined && val !== null) ? val : '';
            };

            setVal('edit_test_name', test.name);
            setVal('edit_test_category_id', test.test_category_id);
            setVal('edit_test_code', test.code);
            setVal('edit_test_sample_type', test.sample_type);
            setVal('edit_test_tat', test.turnaround_time);
            setVal('edit_test_method', test.method);
            setVal('edit_test_branch_id', test.branch_id);
            setVal('edit_test_status', test.status || 'Active');
            setVal('edit_test_description', test.description);

            const syncCb = document.getElementById('edit_sync_branches');
            if (syncCb) {
                syncCb.checked = !test.branch_id;
            }

            const modal = document.getElementById('editTestModal');
            if (modal) modal.style.display = 'flex';
        }

        function closeEditTestModal() {
            const modal = document.getElementById('editTestModal');
            if (modal) modal.style.display = 'none';
        }

        window.addEventListener('click', function(e) {
            const addM = document.getElementById('addTestModal');
            const editM = document.getElementById('editTestModal');
            if (e.target === addM) closeAddTestModal();
            if (e.target === editM) closeEditTestModal();
        });

        // Category to Branch Auto-Selection Handler
        function onCategorySelectChange(selectElem, targetBranchId, targetSyncId) {
            const selectedOpt = selectElem.options[selectElem.selectedIndex];
            if (!selectedOpt) return;
            const branchId = selectedOpt.getAttribute('data-branch-id');
            const branchSelect = document.getElementById(targetBranchId);
            const syncCheckbox = targetSyncId ? document.getElementById(targetSyncId) : null;

            if (branchSelect) {
                if (branchId && branchId !== '') {
                    branchSelect.value = branchId;
                    if (syncCheckbox) {
                        syncCheckbox.checked = false;
                    }
                } else {
                    branchSelect.value = '';
                    if (syncCheckbox) {
                        syncCheckbox.checked = true;
                    }
                }
            }
        }
    </script>
    @include('partials.validation_popup')
</body>
</html>
