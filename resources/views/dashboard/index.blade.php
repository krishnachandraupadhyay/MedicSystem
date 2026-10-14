<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management Dashboard - Hospital Admins</title>
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

        /* ------------------ TOP NAVBAR ------------------ */
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
            color: #0f172a;
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

        /* ------------------ CONTENT CONTAINER ------------------ */
        .content-area {
            padding: 32px;
            flex: 1;
        }

        /* ------------------ PAGE HEADER ------------------ */
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

        .btn-primary-add {
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
            cursor: pointer;
            box-shadow: 0 6px 18px -2px rgba(79, 70, 229, 0.4);
            transition: all 0.2s ease;
        }

        .btn-primary-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px -2px rgba(79, 70, 229, 0.5);
        }

        /* ------------------ MAIN CARD (EXACT REPLICA) ------------------ */
        .table-card {
            background: var(--card-bg);
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-topbar {
            padding: 24px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .card-title-badge {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-badge h2 {
            font-size: 17.5px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.3px;
        }

        .count-bubble {
            background-color: #eef0fe;
            color: #5e59f6;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 16px;
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
            color: #9ca3af;
            font-size: 13.5px;
        }

        .search-box-input {
            width: 100%;
            height: 38px;
            padding-left: 38px;
            padding-right: 14px;
            background: #ffffff;
            border: 1px solid #e5eaf2;
            border-radius: 10px;
            font-size: 13px;
            color: #1f2937;
            transition: all 0.2s ease;
        }

        .search-box-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .search-box-input::placeholder {
            color: #9ca3af;
        }

        /* ------------------ TABLE STYLES ------------------ */
        .table-wrap {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            padding: 15px 28px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #8c97a8;
            border-top: 1px solid #f8fafc;
            border-bottom: 1px solid #f1f4f9;
            background: #ffffff;
        }

        .data-table td {
            padding: 18px 28px;
            font-size: 13.5px;
            color: #1f2937;
            border-bottom: 1px solid #f8fafc;
            vertical-align: middle;
            transition: background 0.15s;
        }

        .data-table tr:hover td {
            background-color: #fafbfc;
        }

        /* Hospital Name Column */
        .hospital-cell {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .hospital-icon-box {
            width: 40px;
            height: 40px;
            background-color: #ede9fe;
            color: #6366f1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .hospital-details {
            display: flex;
            flex-direction: column;
        }

        .hospital-name-text {
            font-weight: 700;
            color: #111827;
            font-size: 14px;
        }

        .hospital-id-text {
            font-size: 12px;
            color: #8c97a8;
            font-weight: 500;
            margin-top: 2px;
        }

        /* Email Column */
        .email-flex {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #374151;
            font-weight: 500;
        }

        .email-flex i {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Role Badge */
        .badge-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background-color: #eef0fe;
            color: #5e59f6;
            padding: 5px 12px;
            border-radius: 16px;
            font-size: 12.5px;
            font-weight: 600;
        }

        .badge-role-pill i {
            font-size: 11.5px;
        }

        /* Status Badge */
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
            background-color: #e8f8f0;
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
        }

        .badge-status-pill.active .dot-indicator {
            background-color: #10b981;
        }

        .badge-status-pill.inactive .dot-indicator {
            background-color: #ef4444;
        }

        /* Actions Column */
        .action-flex {
            display: flex;
            align-items: center;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-edit-icon {
            width: 34px;
            height: 34px;
            background-color: #eef2ff;
            color: #4f46e5;
            border: none;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13.5px;
            transition: all 0.2s ease;
        }

        .btn-edit-icon:hover {
            background-color: #4f46e5;
            color: #ffffff;
            transform: scale(1.05);
        }

        /* AMBER / GOLD SWITCH TOGGLE (EXACT SCREENSHOT) */
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
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
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
            box-shadow: 0 1px 3px rgba(0,0,0,0.25);
        }

        /* Active gold switch */
        .toggle-switch-wrap input:checked + .slider-round {
            background-color: #f59e0b;
        }

        .toggle-switch-wrap input:checked + .slider-round:before {
            transform: translateX(20px);
        }

        /* ------------------ MODALS ------------------ */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            z-index: 100;
        }

        .modal-box {
            background: #ffffff;
            width: 100%;
            max-width: 660px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            overflow: hidden;
            animation: modalFadeIn 0.2s ease;
        }

        .modal-box form {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            flex: 1;
        }

        @keyframes modalFadeIn {
            from { opacity: 0; transform: scale(0.96); }
            to { opacity: 1; transform: scale(1); }
        }

        .modal-head {
            padding: 22px 26px;
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
            background: transparent;
            border: none;
            font-size: 20px;
            color: #94a3b8;
            cursor: pointer;
        }

        .close-x-btn:hover {
            color: #0f172a;
        }

        .modal-content-pad {
            padding: 24px 28px;
            overflow-y: auto;
        }

        .grid-2col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 16px;
        }

        .col-full {
            grid-column: span 2;
        }

        .field-item {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-item label {
            font-size: 12.5px;
            font-weight: 600;
            color: #334155;
        }

        .field-input {
            height: 42px;
            padding: 0 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            color: #1e293b;
        }

        .field-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        .modal-action-bar {
            padding: 18px 26px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }

        .btn-modal-cancel {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #475569;
            cursor: pointer;
        }

        .btn-modal-submit {
            background: linear-gradient(135deg, #5b54fa 0%, #4f46e5 100%);
            color: #ffffff;
            border: none;
            padding: 9px 22px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        /* ------------------ RESPONSIVENESS ------------------ */
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

    <!-- ------------------ SIDEBAR ------------------ -->
    <aside class="sidebar" id="sidebar">
        <!-- Brand Header -->
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
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-table-cells-large"></i>
                        <span class="nav-title">Dashboard</span>
                    </a>
                </li>
                <li>
                    <!-- Active Admin Item (Matching Screenshot) -->
                    <a href="{{ route('dashboard') }}" class="nav-link active">
                        <i class="fa-solid fa-user-shield"></i>
                        <span class="nav-title">Admin</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-building"></i>
                        <span class="nav-title">Branches</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-user-tie"></i>
                        <span class="nav-title">Branch Managers</span>
                    </a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="fa-solid fa-shield-halved"></i>
                        <span class="nav-title">Manager Permissions</span>
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

        <!-- Bottom Log Out -->
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
                <div class="user-dropdown-wrap">
                    <div class="user-profile-btn" id="userProfileBtn" onclick="toggleProfileDropdown(event)" title="Account Menu">
                        <div class="user-avatar-circle">
                            SA
                        </div>
                        <i class="fa-solid fa-chevron-down"></i>
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
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="content-area">
            <!-- PAGE HEADER (Matching Screenshot) -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Hospital Admins</h1>
                    <p>Manage and register all Hospital Administrators</p>
                </div>

                <button class="btn-primary-add" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Admin</span>
                </button>
            </div>

            <!-- REGISTERED ADMINS CARD (EXACT SCREENSHOT REPLICA) -->
            <div class="table-card">
                <!-- Card Header with Count & Search Bar -->
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Registered Admins</h2>
                        <span class="count-bubble" id="countBubble">{{ count($admins) }}</span>
                    </div>

                    <div class="search-box-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" class="search-box-input" id="adminSearchInput" placeholder="Search by name, email..." onkeyup="filterAdmins(this.value)">
                    </div>
                </div>

                <!-- Table Content -->
                <div class="table-wrap">
                    <table class="data-table" id="adminsTable">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>HOSPITAL NAME</th>
                                <th>EMAIL</th>
                                <th>ROLE</th>
                                <th>STATUS</th>
                                <th>CREATED DATE</th>
                                <th style="text-align: right; padding-right: 32px;">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $index => $admin)
                                <tr class="admin-data-row" data-name="{{ strtolower($admin->hospital_name . ' ' . $admin->name) }}" data-email="{{ strtolower($admin->email) }}">
                                    <!-- Index -->
                                    <td style="color: #6b7280; font-weight: 500;">{{ $index + 1 }}</td>

                                    <!-- Hospital Name + ID -->
                                    <td>
                                        <div class="hospital-cell">
                                            <div class="hospital-icon-box">
                                                <i class="fa-solid fa-hospital"></i>
                                            </div>
                                            <div class="hospital-details">
                                                <span class="hospital-name-text">{{ $admin->hospital_name ?? $admin->name }}</span>
                                                <span class="hospital-id-text">ID: #{{ $admin->id }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Email -->
                                    <td>
                                        <div class="email-flex">
                                            <i class="fa-regular fa-envelope"></i>
                                            <span>{{ $admin->email }}</span>
                                        </div>
                                    </td>

                                    <!-- Role -->
                                    <td>
                                        <span class="badge-role-pill">
                                            <i class="fa-solid fa-shield-halved"></i>
                                            <span>{{ $admin->role ?? 'Admin' }}</span>
                                        </span>
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        <span class="badge-status-pill {{ strtolower($admin->status) === 'active' ? 'active' : 'inactive' }}" id="statusPill-{{ $admin->id }}">
                                            <span class="dot-indicator"></span>
                                            <span class="status-name">{{ $admin->status ?? 'Active' }}</span>
                                        </span>
                                    </td>

                                    <!-- Created Date -->
                                    <td style="color: #6b7280; font-weight: 500;">
                                        {{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'Oct 11, 2026' }}
                                    </td>

                                    <!-- Actions (Edit button + Amber Toggle Switch) -->
                                    <td style="text-align: right; padding-right: 30px;">
                                        <div class="action-flex">
                                            <!-- Edit icon button -->
                                            <button type="button" class="btn-edit-icon" title="Edit Admin" onclick="openEditModal({{ json_encode($admin) }})">
                                                <i class="fa-regular fa-pen-to-square"></i>
                                            </button>

                                            <!-- Amber Toggle Switch -->
                                            <label class="toggle-switch-wrap" title="Toggle Status">
                                                <input type="checkbox" {{ $admin->status === 'Active' ? 'checked' : '' }} onchange="toggleStatus({{ $admin->id }}, this)">
                                                <span class="slider-round"></span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 40px; color: #9ca3af;">
                                        No admins found. Click <b>+ Add Admin</b> to register one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- ------------------ MODAL: ADD ADMIN ------------------ -->
    <div class="modal-backdrop" id="addAdminModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add New Administrator</h3>
                <button class="close-x-btn" onclick="closeAddModal()">&times;</button>
            </div>
            <form action="{{ route('admins.store') }}" method="POST">
                @csrf
                <div class="modal-content-pad">
                    <div class="grid-2col">
                        <div class="field-item">
                            <label>Admin Full Name</label>
                            <input type="text" name="name" class="field-input" placeholder="e.g. Iron Admin" required>
                        </div>
                        <div class="field-item">
                            <label>Hospital Name</label>
                            <input type="text" name="hospital_name" class="field-input" placeholder="e.g. RK Memorial Hospital" required>
                        </div>
                        <div class="field-item">
                            <label>Email Address</label>
                            <input type="email" name="email" class="field-input" placeholder="admin@domain.com" required>
                        </div>
                        <div class="field-item">
                            <label>Role</label>
                            <select name="role" class="field-input">
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="field-item">
                            <label>Password</label>
                            <input type="password" name="password" class="field-input" placeholder="Min 6 characters" required>
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Save Admin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ------------------ MODAL: EDIT ADMIN ------------------ -->
    <div class="modal-backdrop" id="editAdminModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Administrator</h3>
                <button class="close-x-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editAdminForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content-pad">
                    <div class="grid-2col">
                        <div class="field-item">
                            <label>Admin Full Name</label>
                            <input type="text" name="name" id="edit_name" class="field-input" required>
                        </div>
                        <div class="field-item">
                            <label>Hospital Name</label>
                            <input type="text" name="hospital_name" id="edit_hospital_name" class="field-input" required>
                        </div>
                        <div class="field-item">
                            <label>Email Address</label>
                            <input type="email" name="email" id="edit_email" class="field-input" required>
                        </div>
                        <div class="field-item">
                            <label>Role</label>
                            <select name="role" id="edit_role" class="field-input">
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" id="edit_status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="field-item">
                            <label>Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="field-input" placeholder="New Password">
                        </div>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Update Admin</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ------------------ SCRIPTS ------------------ -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 900) {
                sidebar.classList.toggle('mobile-open');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        }

        function filterAdmins(query) {
            const term = query.toLowerCase().trim();
            const rows = document.querySelectorAll('.admin-data-row');
            let count = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const email = row.getAttribute('data-email') || '';
                if (name.includes(term) || email.includes(term)) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countBubble = document.getElementById('countBubble');
            if (countBubble) countBubble.innerText = count;
        }

        function toggleStatus(userId, checkbox) {
            fetch(`/dashboard/admins/${userId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                const pill = document.getElementById(`statusPill-${userId}`);
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
            document.getElementById('addAdminModal').style.display = 'flex';
        }

        function closeAddModal() {
            document.getElementById('addAdminModal').style.display = 'none';
        }

        function openEditModal(admin) {
            const form = document.getElementById('editAdminForm');
            form.action = `/dashboard/admins/${admin.id}`;
            document.getElementById('edit_name').value = admin.name || '';
            document.getElementById('edit_hospital_name').value = admin.hospital_name || '';
            document.getElementById('edit_email').value = admin.email || '';
            document.getElementById('edit_role').value = admin.role || 'Admin';
            document.getElementById('edit_status').value = admin.status || 'Active';
            document.getElementById('editAdminModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editAdminModal').style.display = 'none';
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('profileDropdownMenu');
            if (menu) {
                menu.classList.toggle('show');
            }
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
