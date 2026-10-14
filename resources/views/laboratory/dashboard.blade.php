<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Dashboard - {{ $hospital->name ?? 'Diagnostic Lab' }}</title>
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
        .sidebar.collapsed .nav-title,
        .sidebar.collapsed .badge-pill {
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

        .sidebar-scroll-content::-webkit-scrollbar-track {
            background: transparent;
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
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(0,0,0,0.04);
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

        /* TABLE & SUMMARY CARDS */
        .table-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-topbar {
            padding: 22px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-color);
            gap: 16px;
            flex-wrap: wrap;
        }

        .card-title-badge {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title-badge h2 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.2px;
        }

        .count-bubble {
            background: #eef2ff;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .search-box-wrap {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 8px 14px;
            width: 320px;
            max-width: 100%;
            transition: all 0.2s ease;
        }

        .search-box-wrap:focus-within {
            border-color: #6366f1;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .search-box-wrap i {
            color: #94a3b8;
            font-size: 14px;
            margin-right: 10px;
        }

        .search-box-input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 13px;
            color: #0f172a;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table thead tr {
            background-color: #f8fafc;
            border-bottom: 1px solid #edf2f7;
        }

        .data-table th {
            padding: 14px 24px;
            font-size: 11px;
            font-weight: 800;
            color: #64748b;
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background 0.15s ease;
        }

        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }

        .data-table td {
            padding: 16px 24px;
            font-size: 13.5px;
            color: #1e293b;
            vertical-align: middle;
        }

        .sample-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sample-icon-box {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #eef2ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            flex-shrink: 0;
        }

        .sample-id-text {
            font-weight: 700;
            color: #0f172a;
            font-size: 13.5px;
        }

        .sample-dept-sub {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 2px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .status-pill.completed {
            background: #dcfce7;
            color: #15803d;
        }

        .status-pill.in-process {
            background: #fef3c7;
            color: #b45309;
        }

        .status-pill.pending, .status-pill.pending-sample {
            background: #fee2e2;
            color: #b91c1c;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
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

@php
    $rSlug = Auth::user()->role_slug ?? 'laboratory';
@endphp

    @include('partials.laboratory_sidebar')

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        @include('partials.topbar')
        @include('partials.validation_popup')

        <main class="content-area">
            <!-- Page Header Row -->
            <div class="page-header-row">
                <div class="page-heading">
                    <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 6px;">
                        <span style="background: #ecfdf5; color: #059669; font-size: 11.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 5px;">
                            <i class="fa-solid fa-circle-check"></i> Laboratory Module Active
                        </span>
                        @if(!empty($hospital->code))
                            <span style="background: #f1f5f9; color: #475569; font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 20px;">
                                ID: {{ $hospital->code }}
                            </span>
                        @endif
                    </div>
                    <h1>Laboratory Dashboard</h1>
                    <p>Overview of diagnostic specimens, laboratory tests, and clinical reports &bull; <b>{{ $hospital->name ?? 'Diagnostic Lab' }}</b></p>
                </div>

                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    @if($hospital && $hospital->enable_multi_branch && !Auth::user()->is_branch_manager)
                        <a href="{{ url('laboratory/branches') }}" class="btn-view-admin">
                            <i class="fa-solid fa-building"></i>
                            <span>Manage Lab Branches</span>
                        </a>
                    @endif
                    @if(!Auth::user()->is_branch_manager)
                        <a href="{{ url(Auth::user()->role_slug . '/roles') }}" class="btn-view-admin" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 6px 18px -2px rgba(16, 185, 129, 0.4);">
                            <i class="fa-solid fa-user-shield"></i>
                            <span>Roles &amp; Permissions</span>
                        </a>
                    @endif
                </div>
            </div>

            <!-- STATS CARDS -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-info">
                        <span>Total Lab Tests</span>
                        <h3>{{ $stats['total_tests'] }}</h3>
                    </div>
                    <div class="stat-icon purple">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Samples Collected</span>
                        <h3>{{ $stats['samples_collected'] }}</h3>
                    </div>
                    <div class="stat-icon blue">
                        <i class="fa-solid fa-vials"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Pending Analysis</span>
                        <h3>{{ $stats['pending_reports'] }}</h3>
                    </div>
                    <div class="stat-icon amber">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-info">
                        <span>Completed Reports</span>
                        <h3>{{ $stats['completed_reports'] }}</h3>
                    </div>
                    <div class="stat-icon green">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
            </div>

            <!-- TABLE CARD: RECENT DIAGNOSTIC TESTS -->
            <div class="table-card">
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Recent Diagnostic Test Queue</h2>
                        <span class="count-bubble" id="countBubble">{{ count($recentTests) }}</span>
                    </div>

                    <div class="search-box-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-box-input" placeholder="Search patient, sample ID, test name..." onkeyup="filterTests(this.value)">
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="testsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>SAMPLE ID &amp; DEPT</th>
                                <th>PATIENT NAME</th>
                                <th>TEST NAME</th>
                                <th>REFERRING DOCTOR</th>
                                <th>TIME</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTests as $index => $test)
                                <tr class="test-row" data-search="{{ strtolower($test['id'] . ' ' . $test['patient_name'] . ' ' . $test['test_name'] . ' ' . $test['department'] . ' ' . $test['doctor'] . ' ' . $test['status']) }}">
                                    <td style="color: #64748b; font-weight: 500;">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="sample-cell">
                                            <div class="sample-icon-box">
                                                <i class="fa-solid fa-vial"></i>
                                            </div>
                                            <div>
                                                <div class="sample-id-text">{{ $test['id'] }}</div>
                                                <div class="sample-dept-sub">{{ $test['department'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="font-weight: 600; color: #0f172a;">{{ $test['patient_name'] }}</td>
                                    <td>
                                        <span style="font-weight: 600; color: #334155;">{{ $test['test_name'] }}</span>
                                    </td>
                                    <td style="color: #64748b;">
                                        <i class="fa-solid fa-user-doctor" style="color: #94a3b8; font-size: 11px; margin-right: 4px;"></i>
                                        {{ $test['doctor'] }}
                                    </td>
                                    <td style="color: #64748b; font-weight: 500;">{{ $test['time'] }}</td>
                                    <td>
                                        @php
                                            $statusKey = strtolower(str_replace(' ', '-', $test['status']));
                                        @endphp
                                        <span class="status-pill {{ $statusKey }}">
                                            <span class="status-dot"></span>
                                            {{ $test['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 32px; color: #64748b;">
                                        No recent lab specimens found today.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- SCRIPTS -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 900) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('profileDropdownMenu');
            if (menu) menu.classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (menu && menu.classList.contains('show') && btn && !btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        });

        function filterTests(query) {
            const term = (query || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.test-row');
            let count = 0;

            rows.forEach(row => {
                const searchStr = row.getAttribute('data-search') || '';
                if (searchStr.includes(term)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            const bubble = document.getElementById('countBubble');
            if (bubble) bubble.innerText = count;
        }
    </script>
</body>
</html>
