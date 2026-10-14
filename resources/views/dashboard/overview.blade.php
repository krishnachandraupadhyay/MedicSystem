<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management - Superadmin Dashboard</title>
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

        .sidebar-scroll-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }

        .sidebar-scroll-content::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
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
            color: #60a5fa;
            font-weight: 700;
            background: rgba(96, 165, 250, 0.1);
        }

        .sub-link.active i {
            color: #60a5fa;
            font-size: 8px;
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
            position: relative;
            transition: all 0.2s ease;
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
            gap: 8px;
            cursor: pointer;
            padding: 3px 6px 3px 3px;
            border-radius: 20px;
            transition: background 0.2s;
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
            transition: transform 0.2s ease;
        }

        .user-dropdown-wrap {
            position: relative;
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
            from {
                opacity: 0;
                transform: translateY(-6px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
            font-size: 13.5px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
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
            font-size: 13.5px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }

        .btn-view-admin {
            background: linear-gradient(135deg, #5b54fa 0%, #4f46e5 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 10px 22px;
            font-size: 13.5px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            box-shadow: 0 6px 18px -2px rgba(79, 70, 229, 0.4);
            transition: all 0.2s ease;
        }

        .btn-view-admin:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -2px rgba(79, 70, 229, 0.5);
            color: #ffffff;
        }

        /* METRIC CARDS ROW */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 18px;
            margin-bottom: 26px;
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
        .stat-icon.green { background: #ecfdf5; color: #059669; }
        .stat-icon.blue { background: #eff6ff; color: #2563eb; }
        .stat-icon.amber { background: #fffbeb; color: #d97706; }

        /* QUICK SUMMARY CARD */
        .summary-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            padding: 26px;
        }

        .summary-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .summary-head h2 {
            font-size: 18px;
            font-weight: 800;
            color: #111827;
        }

        .hospital-mini-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .hospital-mini-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            background: #f8fafc;
            border-radius: 12px;
            border: 1px solid #f1f5f9;
        }

        .h-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .h-icon {
            width: 38px;
            height: 38px;
            background: #eef2ff;
            color: #4f46e5;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
        }

        .h-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }

        .h-meta {
            font-size: 12px;
            color: #64748b;
        }

        @media (max-width: 900px) {
            .sidebar {
                position: fixed;
                left: -260px;
            }

            .sidebar.mobile-open {
                left: 0;
            }

            .content-area {
                padding: 20px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">
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

            <ul class="nav-menu">
                <li>
                    <!-- 1st Navigation: Dashboard (Active on this page) -->
                    <a href="{{ url(Auth::user()->role_slug . '/dashboard') }}" class="nav-link active">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span class="nav-title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <!-- 2nd Navigation: Admin -> Links to Hospital List -->
                    <a href="{{ url(Auth::user()->role_slug . '/addadmin') }}" class="nav-link">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="nav-title">Admin</span>
                    </a>
                </li>

                <!-- Master Navigation with Submenu Dropdown -->
                <li class="nav-item-dropdown">
                    <a href="javascript:void(0)" class="nav-link nav-dropdown-toggle" onclick="toggleMasterSubmenu(this)">
                        <i class="fa-solid fa-layer-group"></i>
                        <span class="nav-title">Master</span>
                        <i class="fa-solid fa-chevron-right submenu-arrow"></i>
                    </a>
                    <ul class="nav-submenu">
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
                            <a href="{{ url(Auth::user()->role_slug . '/master/pharmacy-types') }}" class="sub-link">
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

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        @include('partials.topbar')

        <main class="content-area">
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Superadmin Dashboard</h1>
                    <p>Overview of hospital branches, medical facilities, and administrators</p>
                </div>

                <!-- Shortcut to Admin Hospitals Table -->
                <a href="{{ url(Auth::user()->role_slug . '/addadmin') }}" class="btn-view-admin">
                    <i class="fa-solid fa-user-shield"></i>
                    <span>Manage Hospitals (Admin)</span>
                </a>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Total Hospitals</span>
                        <h3>{{ $stats['total_hospitals'] }}</h3>
                    </div>
                    <div class="stat-icon purple">
                        <i class="fa-solid fa-hospital"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Active Facilities</span>
                        <h3>{{ $stats['active_hospitals'] }}</h3>
                    </div>
                    <div class="stat-icon green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Registered Admins</span>
                        <h3>{{ $stats['total_admins'] }}</h3>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Active System Users</span>
                        <h3>{{ $stats['active_admins'] }}</h3>
                    </div>
                    <div class="stat-icon amber">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- SUMMARY CARD -->
            <div class="summary-card">
                <div class="summary-head">
                    <h2>Registered Hospital Centers</h2>
                    <a href="{{ route('admin.hospitals') }}" style="color: #4f46e5; text-decoration: none; font-size: 13.5px; font-weight: 700;">
                        Open Admin Table &rarr;
                    </a>
                </div>

                <div class="hospital-mini-list">
                    @foreach($hospitals as $hosp)
                        <div class="hospital-mini-item">
                            <div class="h-info">
                                <div class="h-icon">
                                    <i class="fa-solid fa-hospital"></i>
                                </div>
                                <div>
                                    <div class="h-name">{{ $hosp->name }}</div>
                                    <div class="h-meta">{{ $hosp->city ?? 'Main Branch' }} &bull; {{ $hosp->email }}</div>
                                </div>
                            </div>
                            <div>
                                <span style="background: #e8f8f0; color: #10b981; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 12px;">
                                    {{ $hosp->status }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </main>
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
            if (menu) {
                menu.classList.toggle('show');
            }
        }

        window.addEventListener('click', function(e) {
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
</body>
</html>
