<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Types - RK Hospital Master</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --sidebar-bg: #0E1526;
            --sidebar-text: #8E9BAE;
            --sidebar-hover-bg: #1A2238;
            --sidebar-active-bg: #1E2746;
            --sidebar-text-active: #ffffff;
            --body-bg: #F4F6FA;
            --card-bg: #FFFFFF;
            --text-heading: #111827;
            --text-body: #4B5563;
            --text-muted: #9CA3AF;
            --primary-blue: #4F46E5;
            --border-color: #E2E8F0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--body-bg);
            color: var(--text-body);
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
        .sidebar.collapsed .nav-title,
        .sidebar.collapsed .submenu-arrow,
        .sidebar.collapsed .nav-submenu {
            display: none !important;
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

        /* Sidebar Brand */
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
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .brand-info h2 {
            font-size: 14.5px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.6px;
        }

        .brand-info p {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
            font-weight: 500;
        }

        /* Sidebar Scrollable middle section */
        .sidebar-scroll-content {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            scrollbar-width: thin;
            scrollbar-color: #1e293b transparent;
        }

        .sidebar-scroll-content::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar-scroll-content::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 4px;
        }

        .sidebar-scroll-content::-webkit-scrollbar-thumb:hover {
            background: #334155;
        }

        .menu-heading {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.8px;
            color: #475569;
            padding: 8px 24px 10px;
            text-transform: uppercase;
        }

        .nav-menu {
            list-style: none;
            padding: 0 12px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 14px;
            border-radius: 10px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            margin-bottom: 3px;
            transition: all 0.2s ease;
            position: relative;
        }

        .nav-link i:first-child {
            width: 24px;
            font-size: 16px;
            margin-right: 12px;
            color: inherit;
            display: flex;
            justify-content: center;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: #ffffff;
        }

        .nav-link.active {
            background-color: var(--sidebar-active-bg);
            color: var(--sidebar-text-active);
        }

        .nav-link.active::before {
            content: '';
            position: absolute;
            left: -12px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 22px;
            background: #4f46e5;
            border-radius: 0 4px 4px 0;
        }

        /* Dropdown Master Nav */
        .nav-item-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            cursor: pointer;
        }

        .submenu-arrow {
            font-size: 11px;
            transition: transform 0.25s ease;
            margin-left: auto;
        }

        .nav-item-dropdown.open .submenu-arrow {
            transform: rotate(90deg);
        }

        .nav-submenu {
            list-style: none;
            padding: 4px 0 6px 36px;
            display: none;
        }

        .nav-submenu li {
            margin-bottom: 2px;
        }

        .sub-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-radius: 8px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }

        .sub-link i {
            font-size: 7px;
            color: #64748b;
        }

        .sub-link:hover {
            color: #ffffff;
            background: #1A2238;
        }

        .sub-link.active {
            color: #10b981;
            font-weight: 700;
            background: rgba(16, 185, 129, 0.1);
        }

        .sub-link.active i {
            color: #10b981;
            font-size: 8px;
        }

        /* Sidebar Footer (Fixed at bottom) */
        .sidebar-footer {
            padding: 16px 14px;
            border-top: 1px solid #1A2238;
            background-color: var(--sidebar-bg);
            flex-shrink: 0;
        }

        .logout-btn-nav {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 11px 14px;
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.15);
            border-radius: 10px;
            color: #ef4444;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .logout-btn-nav i {
            width: 24px;
            font-size: 16px;
            margin-right: 12px;
            display: flex;
            justify-content: center;
        }

        .logout-btn-nav:hover {
            background-color: #ef4444;
            color: #ffffff;
            border-color: #ef4444;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25);
        }

        .sidebar.collapsed .sidebar-footer {
            padding: 14px 10px;
        }

        .sidebar.collapsed .logout-btn-nav {
            justify-content: center;
            padding: 11px 0;
        }

        .sidebar.collapsed .logout-btn-nav span {
            display: none;
        }

        .sidebar.collapsed .logout-btn-nav i {
            margin-right: 0;
        }

        /* ------------------ MAIN WRAPPER ------------------ */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            min-width: 0;
        }

        /* Topbar */
        .topbar {
            height: 72px;
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .toggle-btn {
            background: none;
            border: none;
            color: #64748b;
            font-size: 19px;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .toggle-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .icon-circle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            border: 1px solid var(--border-color);
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
            position: relative;
        }

        .badge-dot-red {
            position: absolute;
            top: 7px;
            right: 8px;
            width: 6.5px;
            height: 6.5px;
            background-color: #ef4444;
            border-radius: 50%;
            border: 1.5px solid #ffffff;
        }

        .user-dropdown-wrap {
            position: relative;
        }

        .user-profile-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 3px 6px 3px 3px;
            border-radius: 20px;
            transition: background 0.2s;
        }

        .user-profile-btn:hover {
            background: #f8fafc;
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .user-profile-btn i {
            font-size: 11px;
            color: #94a3b8;
        }

        .profile-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            right: 0;
            width: 210px;
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
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-user-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 6px;
        }

        .dropdown-avatar-circle {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            flex-shrink: 0;
        }

        .dropdown-user-details {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .dropdown-role-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
        }

        .dropdown-role-sub {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 2px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
        }

        .dropdown-item-btn:hover {
            background: #fee2e2;
            color: #ef4444;
        }

        .dropdown-item-btn i {
            font-size: 14px;
        }

        /* ------------------ CONTENT CONTAINER ------------------ */
        .content-area {
            padding: 32px;
            flex: 1;
        }

        .page-header-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 26px;
        }

        .page-heading h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -0.4px;
        }

        .page-heading p {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            margin-top: 3px;
        }

        .btn-primary-add {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            border: none;
            padding: 11px 22px;
            border-radius: 12px;
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.35);
            transition: all 0.2s ease;
        }

        .btn-primary-add:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(16, 185, 129, 0.45);
        }

        /* ------------------ DATA TABLE CARD ------------------ */
        .table-card {
            background: var(--card-bg);
            border-radius: 18px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.04);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-topbar {
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .card-title-badge {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-badge h2 {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }

        .count-bubble {
            background-color: #f1f5f9;
            color: #475569;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
        }

        .search-box-wrap {
            position: relative;
            width: 280px;
        }

        .search-box-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 14px;
        }

        .search-box-input {
            width: 100%;
            padding: 9px 14px 9px 38px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 13px;
            outline: none;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        .search-box-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table thead th {
            padding: 14px 28px;
            font-size: 11.5px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            border-bottom: 1px solid #f1f5f9;
            background-color: #fafbfd;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .data-table tbody td {
            padding: 16px 28px;
            font-size: 13.5px;
            color: #334155;
            vertical-align: middle;
        }

        .badge-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .badge-status-pill.active {
            background-color: #ecfdf5;
            color: #10b981;
        }

        .badge-status-pill.inactive {
            background-color: #fef2f2;
            color: #ef4444;
        }

        .dot-indicator {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: currentColor;
        }

        .action-flex {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 14px;
        }

        .btn-edit-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #10b981;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-edit-icon:hover {
            background-color: #10b981;
            color: #ffffff;
        }

        /* AMBER TOGGLE SWITCH */
        .toggle-switch-wrap {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .toggle-switch-wrap input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider-round {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #cbd5e1;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 24px;
        }

        .slider-round:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .toggle-switch-wrap input:checked + .slider-round {
            background-color: #f59e0b;
        }

        .toggle-switch-wrap input:checked + .slider-round:before {
            transform: translateX(20px);
        }

        /* ------------------ MODALS ------------------ */
        .modal-backdrop {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
        }

        .modal-box {
            background-color: #ffffff;
            border-radius: 18px;
            width: 100%;
            max-width: 520px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            animation: modalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes modalPop {
            0% { transform: scale(0.95); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        .modal-head {
            padding: 20px 26px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
        }

        .modal-head h3 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
        }

        .close-x-btn {
            background: none;
            border: none;
            font-size: 22px;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        .close-x-btn:hover {
            color: #0f172a;
        }

        .modal-content-pad {
            padding: 24px 26px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .field-item {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        .field-item label {
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }

        .field-input {
            width: 100%;
            padding: 11px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            font-size: 13.5px;
            color: #0f172a;
            outline: none;
            transition: all 0.2s;
            background: #ffffff;
        }

        .field-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
        }

        .modal-action-bar {
            padding: 16px 26px;
            background-color: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-modal-cancel {
            padding: 10px 18px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #475569;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-modal-submit {
            padding: 10px 22px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }

        .toast-msg {
            padding: 12px 18px;
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 600;
            font-size: 13.5px;
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }
    </style>
</head>
<body>

    <!-- ------------------ SIDEBAR ------------------ -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand Header (Fixed at top) -->
        <div class="brand-block">
            <div class="brand-logo-icon">
                <i class="fa-solid fa-hospital"></i>
            </div>
            <div class="brand-info">
                <h2>HOSPITAL SYSTEM</h2>
                <p>Hospital Management Dashboard</p>
            </div>
        </div>

        <!-- Scrollable Middle Menu Section -->
        <div class="sidebar-scroll-content">
            <div class="menu-heading">MAIN MENU</div>

            <!-- Navigation Menu Items -->
            <ul class="nav-menu">
                <li>
                    <a href="{{ url(Auth::user()->role_slug . '/dashboard') }}" class="nav-link">
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

                <!-- Master Navigation with Submenu Dropdown -->
                <li class="nav-item-dropdown open">
                    <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle active" onclick="toggleMasterSubmenu(this)">
                        <i class="fa-solid fa-layer-group"></i>
                        <span class="nav-title">Master</span>
                        <i class="fa-solid fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="nav-submenu" style="display: block;">
                        <li>
                            <a href="{{ url(Auth::user()->role_slug . '/master/medical-types') }}" class="sub-link">
                                <i class="fa-solid fa-circle-dot"></i>
                                <span>Medical Type</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(Auth::user()->role_slug . '/master/hospital-types') }}" class="sub-link">
                                <i class="fa-solid fa-circle-dot"></i>
                                <span>Hospital Type</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(Auth::user()->role_slug . '/master/laboratory-types') }}" class="sub-link">
                                <i class="fa-solid fa-circle-dot"></i>
                                <span>Laboratory Type</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url(Auth::user()->role_slug . '/master/pharmacy-types') }}" class="sub-link active">
                                <i class="fa-solid fa-circle-dot"></i>
                                <span>Pharmacy Type</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ url(Auth::user()->role_slug . '/branches') }}" class="nav-link">
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
                    <a href="{{ url(Auth::user()->role_slug . '/admin-permission') }}" class="nav-link">
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
                        <i class="fa-regular fa-clock"></i>
                        <span class="nav-title">Shift Timing</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span class="nav-title">Holiday</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-hospital-user"></i>
                        <span class="nav-title">Patients</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-clipboard-list"></i>
                        <span class="nav-title">Reports</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Logout Section (Fixed at bottom) -->
        <div class="sidebar-footer">
            <button type="button" class="logout-btn-nav" onclick="triggerLogout()">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                <span>Logout</span>
            </button>
            <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </aside>

    <!-- ------------------ MAIN WRAPPER ------------------ -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
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

                <!-- Profile Badge SA with Dropdown -->
                <div class="user-dropdown-wrap">
                    <div class="user-profile-btn" id="userProfileBtn" onclick="toggleProfileDropdown(event)" title="Account Menu">
                        <div class="user-avatar-circle">
                            SA
                        </div>
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>

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
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="content-area">
            @if(session('success'))
                <div class="toast-msg">
                    <div><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}</div>
                    <button style="background:none; border:none; cursor:pointer; color:inherit;" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            <!-- PAGE HEADER -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Pharmacy Types</h1>
                    <p>Manage pharmacy classifications (Retail Pharmacy, Hospital Pharmacy, Clinical Pharmacy, etc.)</p>
                </div>

                <button class="btn-primary-add" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Pharmacy Type</span>
                </button>
            </div>

            <!-- TABLE CARD -->
            <div class="table-card">
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Registered Pharmacy Types</h2>
                        <span class="count-bubble" id="countBubble">{{ count($items) }}</span>
                    </div>

                    <div class="search-box-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-box-input" placeholder="Search by name, code..." onkeyup="filterItems(this.value)">
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>PHARMACY TYPE NAME</th>
                                <th>CODE</th>
                                <th>DESCRIPTION</th>
                                <th>STATUS</th>
                                <th>CREATED DATE</th>
                                <th style="text-align: right; padding-right: 32px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($items as $index => $item)
                                <tr class="data-row" data-search="{{ strtolower($item->name . ' ' . $item->code) }}">
                                    <td style="color: #6b7280; font-weight: 500;">{{ $index + 1 }}</td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <div style="width: 34px; height: 34px; border-radius: 8px; background: #ecfdf5; color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 15px;">
                                                <i class="fa-solid fa-prescription-bottle-medical"></i>
                                            </div>
                                            <span style="font-weight: 700; color: #0f172a;">{{ $item->name }}</span>
                                        </div>
                                    </td>
                                    <td><span style="background: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-size: 12px; font-weight: 600; color: #475569;">{{ $item->code }}</span></td>
                                    <td style="color: #64748b;">{{ $item->description ?? '-' }}</td>
                                    <td>
                                        <span class="badge-status-pill {{ strtolower($item->status) === 'active' ? 'active' : 'inactive' }}" id="statusPill-{{ $item->id }}">
                                            <span class="dot-indicator"></span>
                                            <span class="status-name">{{ $item->status }}</span>
                                        </span>
                                    </td>
                                    <td style="color: #6b7280;">{{ $item->created_at ? $item->created_at->format('M d, Y') : 'Oct 11, 2026' }}</td>
                                    <td style="text-align: right; padding-right: 30px;">
                                        <div class="action-flex">
                                            <button type="button" class="btn-edit-icon" title="Edit" onclick="openEditModal({{ json_encode($item) }})">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>
                                            <label class="toggle-switch-wrap" title="Toggle Status">
                                                <input type="checkbox" {{ $item->status === 'Active' ? 'checked' : '' }} onchange="toggleStatus({{ $item->id }}, this)">
                                                <span class="slider-round"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9ca3af;">
                                        No pharmacy types found. Click <b>+ Add Pharmacy Type</b> to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- ADD MODAL -->
    <div class="modal-backdrop" id="addModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add Pharmacy Type</h3>
                <button class="close-x-btn" onclick="closeAddModal()">&times;</button>
            </div>
            <form action="{{ url(Auth::user()->role_slug . '/master/pharmacy-types') }}" method="POST">
                @csrf
                <div class="modal-content-pad">
                    <div class="field-item">
                        <label>Pharmacy Type Name</label>
                        <input type="text" name="name" class="field-input" placeholder="e.g. Retail Pharmacy, Hospital Pharmacy, Online Pharmacy" required>
                    </div>
                    <div class="field-item">
                        <label>Code (optional)</label>
                        <input type="text" name="code" class="field-input" placeholder="e.g. PT-HP">
                    </div>
                    <div class="field-item">
                        <label>Description</label>
                        <input type="text" name="description" class="field-input" placeholder="Short description">
                    </div>
                    <div class="field-item">
                        <label>Status</label>
                        <select name="status" class="field-input">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Save Type</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal-backdrop" id="editModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Pharmacy Type</h3>
                <button class="close-x-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content-pad">
                    <div class="field-item">
                        <label>Pharmacy Type Name</label>
                        <input type="text" name="name" id="edit_name" class="field-input" required>
                    </div>
                    <div class="field-item">
                        <label>Code</label>
                        <input type="text" name="code" id="edit_code" class="field-input">
                    </div>
                    <div class="field-item">
                        <label>Description</label>
                        <input type="text" name="description" id="edit_description" class="field-input">
                    </div>
                    <div class="field-item">
                        <label>Status</label>
                        <select name="status" id="edit_status" class="field-input">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Update Type</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 900) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        function toggleMasterSubmenu(element) {
            const parent = element.closest('.nav-item-dropdown');
            const submenu = parent.querySelector('.nav-submenu');
            if (submenu.style.display === 'none' || !submenu.style.display) {
                submenu.style.display = 'block';
                parent.classList.add('open');
            } else {
                submenu.style.display = 'none';
                parent.classList.remove('open');
            }
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('profileDropdownMenu');
            if (menu) menu.classList.toggle('show');
        }

        function filterItems(query) {
            const term = query.toLowerCase().trim();
            const rows = document.querySelectorAll('.data-row');
            let count = 0;
            rows.forEach(row => {
                const text = row.getAttribute('data-search') || '';
                if (text.includes(term)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });
            const countBubble = document.getElementById('countBubble');
            if (countBubble) countBubble.innerText = count;
        }

        const currentRoleSlug = '{{ Auth::user()->role_slug ?? "superadmin" }}';

        function toggleStatus(id, checkbox) {
            fetch(`/${currentRoleSlug}/master/pharmacy-types/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const pill = document.getElementById(`statusPill-${id}`);
                if (pill && data.success) {
                    if (data.status === 'Active') {
                        pill.className = 'badge-status-pill active';
                        pill.querySelector('.status-name').innerText = 'Active';
                        checkbox.checked = true;
                    } else {
                        pill.className = 'badge-status-pill inactive';
                        pill.querySelector('.status-name').innerText = 'Inactive';
                        checkbox.checked = false;
                    }
                }
            })
            .catch(err => {
                console.error(err);
                checkbox.checked = !checkbox.checked;
            });
        }

        function openAddModal() {
            document.getElementById('addModal').style.display = 'flex';
        }

        function closeAddModal() {
            document.getElementById('addModal').style.display = 'none';
        }

        function openEditModal(item) {
            const form = document.getElementById('editForm');
            form.action = `/${currentRoleSlug}/master/pharmacy-types/${item.id}`;
            document.getElementById('edit_name').value = item.name || '';
            document.getElementById('edit_code').value = item.code || '';
            document.getElementById('edit_description').value = item.description || '';
            document.getElementById('edit_status').value = item.status || 'Active';
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        window.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-backdrop')) {
                e.target.style.display = 'none';
            }
            const menu = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (menu && menu.classList.contains('show')) {
                if (!menu.contains(e.target) && !btn.contains(e.target)) {
                    menu.classList.remove('show');
                }
            }
        });

        function triggerLogout() {
            if (confirm('Do you want to log out?')) {
                document.getElementById('logoutForm').submit();
            }
        }
    </script>
    @include('partials.validation_popup')
</body>
</html>
