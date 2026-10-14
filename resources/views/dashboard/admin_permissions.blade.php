<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Permissions - Hospital System</title>
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
        .sidebar.collapsed .submenu-arrow {
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
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.35);
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
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            border-radius: 10px;
            margin-bottom: 4px;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background-color: var(--sidebar-hover-bg);
            color: #cbd5e1;
        }

        .nav-link.active {
            background-color: #4f46e5;
            color: var(--sidebar-text-active);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
        }

        .nav-link i {
            width: 24px;
            font-size: 16px;
            margin-right: 12px;
        }

        .nav-item-dropdown .nav-dropdown-toggle {
            position: relative;
        }

        .submenu-arrow {
            margin-left: auto;
            font-size: 12px;
            transition: transform 0.2s ease;
        }

        .nav-item-dropdown.open .submenu-arrow {
            transform: rotate(90deg);
        }

        .nav-submenu {
            list-style: none;
            padding: 4px 0 6px 36px;
            display: none;
        }

        .nav-item-dropdown.open .nav-submenu {
            display: block;
        }

        .sub-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            font-size: 12.5px;
            color: #94a3b8;
            text-decoration: none;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .sub-link:hover, .sub-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.07);
            flex-shrink: 0;
            background-color: var(--sidebar-bg);
        }

        .btn-logout {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover {
            background: #ef4444;
            color: #ffffff;
        }

        /* ------------------ MAIN WRAPPER ------------------ */
        .main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow-y: auto;
        }

        .topbar {
            height: 72px;
            background: #ffffff;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 28px;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .toggle-btn {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            cursor: pointer;
            color: #475569;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .toggle-btn:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .icon-circle-btn {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #64748b;
            cursor: pointer;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .icon-circle-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .badge-dot-red {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            background: #ef4444;
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
            padding: 4px;
            border-radius: 20px;
            transition: background 0.2s;
        }

        .user-avatar-circle {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: #ffffff;
            font-weight: 700;
            font-size: 13px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-dropdown-menu {
            position: absolute;
            right: 0;
            top: 48px;
            width: 200px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 8px;
            display: none;
            z-index: 100;
        }

        .profile-dropdown-menu.show {
            display: block;
        }

        .dropdown-user-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
        }

        .dropdown-role-name {
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            display: block;
        }

        .dropdown-role-sub {
            font-size: 11px;
            color: #64748b;
        }

        .dropdown-divider {
            height: 1px;
            background: #f1f5f9;
            margin: 6px 0;
        }

        .dropdown-item-btn {
            width: 100%;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            background: transparent;
            border: none;
            border-radius: 8px;
            color: #ef4444;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .dropdown-item-btn:hover {
            background: #fef2f2;
        }

        /* ------------------ CONTENT AREA ------------------ */
        .content-area {
            padding: 28px;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
        }

        .page-header-row {
            margin-bottom: 24px;
        }

        .page-heading h1 {
            font-size: 23px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .page-heading p {
            font-size: 13.5px;
            color: #64748b;
            margin-top: 4px;
        }

        /* Toast */
        .toast-msg {
            background: #10b981;
            color: #ffffff;
            padding: 12px 18px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            box-shadow: 0 4px 14px rgba(16, 185, 129, 0.25);
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* FULL-WIDTH VERTICAL LAYOUT (col-sm-12, col-md-12) */
        .permissions-vertical-wrap {
            display: flex;
            flex-direction: column;
            gap: 24px;
            width: 100%;
        }

        /* LEFT PANEL: ADMIN LIST */
        .table-card {
            background: #ffffff;
            border: 1px solid #eef2f7;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .card-topbar {
            padding: 20px 24px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .card-topbar-row1 {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .card-title-badge {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-title-badge h2 {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
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
            position: relative;
            min-width: 260px;
            max-width: 360px;
            width: 100%;
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
            height: 40px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 0 14px 0 38px;
            font-size: 13.5px;
            color: #1e293b;
            outline: none;
            transition: all 0.2s;
        }

        .search-box-input:focus {
            background: #ffffff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.12);
        }

        /* Topbar Actions & Medical Type Dropdown */
        .topbar-actions-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .med-type-dropdown-wrap {
            position: relative;
            display: inline-flex;
            align-items: center;
        }

        .med-type-dropdown-wrap .med-type-icon {
            position: absolute;
            left: 14px;
            color: #6366f1;
            font-size: 13.5px;
            pointer-events: none;
            z-index: 1;
        }

        .med-type-select {
            height: 40px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            padding: 0 36px 0 38px;
            font-size: 13px;
            font-weight: 700;
            color: #1e293b;
            outline: none;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: all 0.2s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
            min-width: 170px;
        }

        .med-type-select:hover {
            border-color: #6366f1;
            background-color: #f8fafc;
        }

        .med-type-select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        .med-type-dropdown-wrap .med-type-arrow {
            position: absolute;
            right: 14px;
            color: #94a3b8;
            font-size: 11px;
            pointer-events: none;
            transition: transform 0.2s;
        }

        .med-type-dropdown-wrap:hover .med-type-arrow {
            color: #4f46e5;
        }

        /* TABLE */
        .table-wrap {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 11.5px;
            font-weight: 700;
            letter-spacing: 0.6px;
            text-transform: uppercase;
            padding: 12px 18px;
            border-bottom: 1px solid #eef2f7;
        }

        .data-table td {
            padding: 14px 18px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            color: #334155;
            vertical-align: middle;
        }

        .admin-row-item {
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .admin-row-item:hover {
            background: #f8fafc;
        }

        .admin-row-item.selected-row {
            background: #eef2ff !important;
            border-left: 4px solid #4f46e5;
        }

        .facility-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .facility-avatar-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .avatar-hospital {
            background: #eff6ff;
            color: #2563eb;
        }

        .avatar-laboratory {
            background: #f0fdf4;
            color: #16a34a;
        }

        .avatar-pharmacy {
            background: #fdf4ff;
            color: #c026d3;
        }

        .facility-info-block {
            display: flex;
            flex-direction: column;
        }

        .facility-title {
            font-weight: 700;
            color: #0f172a;
            font-size: 13.5px;
        }

        .facility-code {
            font-size: 11px;
            color: #64748b;
            font-weight: 500;
        }

        .type-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.3px;
        }

        .type-badge.hospital {
            background: #e0f2fe;
            color: #0369a1;
        }

        .type-badge.laboratory {
            background: #dcfce7;
            color: #15803d;
        }

        .type-badge.pharmacy {
            background: #fae8ff;
            color: #a21caf;
        }

        .perm-mini-tags {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .perm-tag {
            font-size: 11px;
            font-weight: 600;
            padding: 2px 7px;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .perm-tag.on {
            background: #dcfce7;
            color: #166534;
        }

        .perm-tag.off {
            background: #f1f5f9;
            color: #94a3b8;
        }

        /* FULL WIDTH PERMISSION TAB PANEL (OPENS NICHE WHEN CLICKING HOSPITAL NAME) */
        .permission-control-panel {
            width: 100%;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06);
            overflow: hidden;
            transition: all 0.3s ease;
            animation: slideDownTab 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideDownTab {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .panel-head {
            padding: 18px 24px;
            background: linear-gradient(135deg, #0e1526 0%, #1e293b 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }

        .panel-head-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .panel-head-icon {
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #ffffff;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .panel-head-details h3 {
            font-size: 16px;
            font-weight: 800;
            letter-spacing: -0.2px;
            line-height: 1.3;
            color: #ffffff;
        }

        .panel-head-details p {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 3px;
        }

        .panel-head-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .active-pill-indicator {
            background: rgba(16, 185, 129, 0.18);
            border: 1px solid rgba(16, 185, 129, 0.35);
            color: #6ee7b7;
            font-size: 12px;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-tab-close {
            background: rgba(255, 255, 255, 0.12);
            color: #f1f5f9;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-tab-close:hover {
            background: rgba(239, 68, 68, 0.2);
            border-color: rgba(239, 68, 68, 0.4);
            color: #fca5a5;
        }

        .panel-body {
            padding: 24px;
        }

        .panel-section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #475569;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* 3 CARDS IN A ROW FOR FULL WIDTH (col-12) */
        .perm-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
            margin-bottom: 18px;
        }

        @media (max-width: 1024px) {
            .perm-cards-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 640px) {
            .perm-cards-grid {
                grid-template-columns: 1fr;
            }
        }

        /* PERMISSION TOGGLE CARD */
        .perm-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 165px;
            transition: all 0.25s ease;
        }

        .perm-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
            transform: translateY(-2px);
        }

        .perm-card.active {
            background: #ffffff;
            border-color: #818cf8;
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.08);
        }

        .perm-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .perm-card-title-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .perm-card-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .perm-icon-lab {
            background: #e0f2fe;
            color: #0284c7;
        }

        .perm-icon-branch {
            background: #fae8ff;
            color: #a855f7;
        }

        .perm-icon-pharm {
            background: #ecfdf5;
            color: #059669;
        }

        .perm-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .perm-card-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.5;
            margin-top: 6px;
            margin-bottom: 12px;
        }

        /* MODERN SWITCH */
        .switch-wrap {
            position: relative;
            display: inline-block;
            width: 44px;
            height: 24px;
        }

        .switch-wrap input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 24px;
        }

        .switch-slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .25s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        input:checked + .switch-slider {
            background-color: #4f46e5;
        }

        input:checked + .switch-slider:before {
            transform: translateX(20px);
        }

        .perm-status-indicator {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 4px;
            margin-top: 6px;
        }

        .perm-status-indicator.enabled {
            background: #dcfce7;
            color: #15803d;
        }

        .perm-status-indicator.disabled {
            background: #f1f5f9;
            color: #64748b;
        }

        .panel-footer-actions {
            margin-top: 14px;
            padding-top: 16px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
        }

        .panel-footer-hint {
            font-size: 12px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-save-all {
            width: auto;
            min-width: 180px;
            padding: 11px 24px;
            background: #4f46e5;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s;
        }

        .btn-save-all:hover {
            background: #4338ca;
            transform: translateY(-1px);
        }

        .facility-title-link {
            font-weight: 700;
            color: #0f172a;
            font-size: 13.5px;
            cursor: pointer;
            transition: color 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .facility-title-link:hover {
            color: #4f46e5;
        }

        .click-hint {
            font-size: 10.5px;
            font-weight: 600;
            color: #4f46e5;
            background: #eef2ff;
            padding: 2px 7px;
            border-radius: 4px;
            letter-spacing: 0.2px;
        }

        .btn-manage-perm {
            background: #f8fafc;
            color: #4f46e5;
            border: 1px solid #e0e7ff;
            font-size: 12px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 8px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .btn-manage-perm:hover {
            background: #4f46e5;
            color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 2px 8px rgba(79, 70, 229, 0.2);
        }

        .empty-selected-state {
            padding: 40px 20px;
            text-align: center;
            color: #94a3b8;
        }

        .empty-selected-state i {
            font-size: 36px;
            color: #cbd5e1;
            margin-bottom: 12px;
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
                    <!-- Active on this page: Admin Permissions -->
                    <a href="{{ url(Auth::user()->role_slug . '/admin-permission') }}" class="nav-link active">
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
            </ul>
        </div>

        <!-- Fixed Logout at bottom -->
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

    <!-- ------------------ MAIN WRAPPER ------------------ -->
    <div class="main-wrapper">
        <!-- Topbar -->
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
                            <div class="user-avatar-circle" style="width: 32px; height: 32px; font-size: 12px;">SA</div>
                            <div class="dropdown-user-details">
                                <span class="dropdown-role-name">Super Admin</span>
                                <span class="dropdown-role-sub">{{ Auth::user()->email ?? 'admin@gmail.com' }}</span>
                            </div>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item-btn">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="content-area">
            <div id="ajaxToastMsg" class="toast-msg" style="display: none;">
                <div><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> <span id="toastText">Status updated!</span></div>
                <button style="background:none; border:none; cursor:pointer; color:inherit;" onclick="this.parentElement.style.display='none'"><i class="fa-solid fa-xmark"></i></button>
            </div>

            @if(session('success'))
                <div class="toast-msg">
                    <div><i class="fa-solid fa-circle-check" style="margin-right: 8px;"></i> {{ session('success') }}</div>
                    <button style="background:none; border:none; cursor:pointer; color:inherit;" onclick="this.parentElement.remove()"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            <!-- Page Header -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Admin Permissions</h1>
                    <p>Select any registered administrator or facility to configure module permissions, Laboratory access, and Multi-Branch operations.</p>
                </div>
            </div>

            <!-- FULL-WIDTH VERTICAL LAYOUT (col-sm-12 and col-md-12) -->
            <div class="permissions-vertical-wrap col-12 col-md-12 col-sm-12">
                
                <!-- SECTION 1: FULL-WIDTH ADMIN LIST & FILTERS TABLE -->
                <div class="table-card col-12 col-md-12 col-sm-12">
                    <div class="card-topbar">
                        <div class="card-topbar-row1">
                            <div class="card-title-badge">
                                <h2>Registered Admin Facilities</h2>
                                <span class="count-bubble" id="adminCountBubble">{{ count($hospitals) }}</span>
                            </div>

                            <div class="topbar-actions-right">
                                <!-- Medical Type Filter Dropdown -->
                                <div class="med-type-dropdown-wrap">
                                    <i class="fa-solid fa-layer-group med-type-icon"></i>
                                    <select id="medTypeSelect" class="med-type-select" onchange="filterByMedType(this.value)">
                                        <option value="all">All Types</option>
                                        @foreach($medicalTypes as $mt)
                                            <option value="{{ strtolower($mt->name) }}" {{ strtolower($medicalTypeFilter ?? '') === strtolower($mt->name) ? 'selected' : '' }}>
                                                {{ $mt->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i class="fa-solid fa-chevron-down med-type-arrow"></i>
                                </div>

                                <div class="search-box-wrap">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                    <input type="text" class="search-box-input" id="adminSearchInput" placeholder="Filter by name, ID, city..." onkeyup="filterAdmins(this.value)">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table" id="adminTable">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>ADMIN / FACILITY (CLICK NAME TO OPEN PERMISSIONS)</th>
                                    <th>MEDICAL TYPE</th>
                                    <th>LOCATION</th>
                                    <th>PERMISSIONS STATUS</th>
                                    <th style="text-align: right; padding-right: 24px;">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="adminTableBody">
                                @forelse($hospitals as $index => $hospital)
                                    @php
                                        $medType = $hospital->medical_type ?? 'Hospital';
                                        $avatarClass = 'avatar-' . strtolower($medType);
                                        $iconClass = ($medType === 'Laboratory') ? 'fa-flask-vial' : (($medType === 'Pharmacy') ? 'fa-pills' : 'fa-hospital');
                                    @endphp
                                    <tr class="admin-row-item {{ $loop->first ? 'selected-row' : '' }}" 
                                        id="admin-row-{{ $hospital->id }}"
                                        data-id="{{ $hospital->id }}"
                                        data-name="{{ strtolower($hospital->name) }}"
                                        data-code="{{ strtolower($hospital->code ?? '') }}"
                                        data-email="{{ strtolower($hospital->email ?? '') }}"
                                        data-city="{{ strtolower($hospital->city ?? '') }}"
                                        data-medtype="{{ strtolower($medType) }}"
                                        data-raw-name="{{ $hospital->name }}"
                                        data-raw-code="{{ $hospital->code ?? 'HOSP-'.$hospital->id }}"
                                        data-raw-medtype="{{ $medType }}"
                                        data-raw-type="{{ $hospital->type ?? 'General' }}"
                                        data-raw-city="{{ $hospital->city ?? 'N/A' }}"
                                        data-raw-email="{{ $hospital->email ?? 'N/A' }}"
                                        data-lab="{{ $hospital->enable_laboratory ? '1' : '0' }}"
                                        data-branch="{{ $hospital->enable_multi_branch ? '1' : '0' }}"
                                        data-pharm="{{ $hospital->enable_pharmacy ? '1' : '0' }}"
                                        onclick="selectAndOpenAdmin({{ $hospital->id }})">
                                        
                                        <td style="color: #64748b; font-weight: 600;">{{ $index + 1 }}</td>
                                        
                                        <td>
                                            <div class="facility-name-cell">
                                                <div class="facility-avatar-icon {{ $avatarClass }}">
                                                    <i class="fa-solid {{ $iconClass }}"></i>
                                                </div>
                                                <div class="facility-info-block">
                                                    <span class="facility-title-link" title="Click to open permissions tab below">
                                                        {{ $hospital->name }}
                                                        <span class="click-hint"><i class="fa-solid fa-arrow-down"></i> Open Tab</span>
                                                    </span>
                                                    <span class="facility-code">{{ $hospital->code ?? 'ID #'.$hospital->id }} • {{ $hospital->type ?? 'General' }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td>
                                            <span class="type-badge {{ strtolower($medType) }}">{{ $medType }}</span>
                                        </td>

                                        <td style="color: #64748b;">
                                            <i class="fa-solid fa-location-dot" style="font-size: 11px; margin-right: 4px;"></i> {{ $hospital->city ?? 'Not Set' }}
                                        </td>

                                        <td>
                                            <div class="perm-mini-tags">
                                                <span class="perm-tag {{ $hospital->enable_laboratory ? 'on' : 'off' }}" id="mini-lab-{{ $hospital->id }}">
                                                    <i class="fa-solid fa-flask-vial"></i> Lab: {{ $hospital->enable_laboratory ? 'ON' : 'OFF' }}
                                                </span>
                                                <span class="perm-tag {{ $hospital->enable_multi_branch ? 'on' : 'off' }}" id="mini-branch-{{ $hospital->id }}">
                                                    <i class="fa-solid fa-code-branch"></i> Multi-Branch: {{ $hospital->enable_multi_branch ? 'ON' : 'OFF' }}
                                                </span>
                                            </div>
                                        </td>

                                        <td style="text-align: right; padding-right: 24px;">
                                            <button type="button" class="btn-manage-perm" onclick="event.stopPropagation(); selectAndOpenAdmin({{ $hospital->id }})">
                                                <i class="fa-solid fa-sliders"></i> Permissions <i class="fa-solid fa-chevron-down"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                                            No administrator facilities registered yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- FULL-WIDTH SECTION 2: PERMISSION CONTROL TAB (OPENS NICHE WHEN CLICKING HOSPITAL NAME) -->
                @php
                    $firstAdmin = $hospitals->first();
                @endphp
                <div class="permission-control-panel col-12 col-md-12 col-sm-12" id="permissionPanel" style="{{ $firstAdmin ? '' : 'display: none;' }}">

                    @if($firstAdmin)
                        <!-- Panel Header -->
                        <div class="panel-head">
                            <div class="panel-head-left">
                                <div class="panel-head-icon" id="panelHeaderIcon">
                                    @if(($firstAdmin->medical_type ?? 'Hospital') === 'Laboratory')
                                        <i class="fa-solid fa-flask-vial"></i>
                                    @elseif(($firstAdmin->medical_type ?? 'Hospital') === 'Pharmacy')
                                        <i class="fa-solid fa-pills"></i>
                                    @else
                                        <i class="fa-solid fa-hospital"></i>
                                    @endif
                                </div>
                                <div class="panel-head-details">
                                    <h3 id="panelAdminName">{{ $firstAdmin->name }}</h3>
                                    <p id="panelAdminSub">{{ $firstAdmin->code ?? 'HOSP-01' }} • <span id="panelAdminMedType">{{ $firstAdmin->medical_type ?? 'Hospital' }}</span> • <span id="panelAdminLocation"><i class="fa-solid fa-location-dot" style="font-size: 11px;"></i> {{ $firstAdmin->city ?? 'N/A' }}</span></p>
                                </div>
                            </div>
                            <div class="panel-head-right">
                                <span class="active-pill-indicator">
                                    <i class="fa-solid fa-circle-check"></i> Module Permissions Active
                                </span>
                                <button type="button" class="btn-tab-close" onclick="closePermissionTab()" title="Close Permissions Tab">
                                    <i class="fa-solid fa-xmark"></i> Close Tab
                                </button>
                            </div>
                        </div>

                        <div class="panel-body">
                            <div class="panel-section-title">
                                <div>
                                    <i class="fa-solid fa-sliders"></i> Module Permissions Configuration
                                </div>
                                <span style="font-size: 11.5px; color: #64748b; font-weight: 500; text-transform: none;">
                                    Toggling a module updates permission instantly via AJAX
                                </span>
                            </div>

                            <!-- Form for Save All -->
                            <form id="savePermissionsForm" method="POST" action="{{ url(Auth::user()->role_slug . '/admin-permission/' . $firstAdmin->id) }}">
                                @csrf
                                @method('PUT')

                                <input type="hidden" id="currentAdminId" value="{{ $firstAdmin->id }}">

                                <!-- 3 RESPONSIVE CARDS IN A ROW FOR FULL WIDTH (col-12) -->
                                <div class="perm-cards-grid">
                                    <!-- 1. LABORATORY PERMISSION CARD -->
                                    <div class="perm-card {{ $firstAdmin->enable_laboratory ? 'active' : '' }}" id="card_perm_lab">
                                        <div>
                                            <div class="perm-card-header">
                                                <div class="perm-card-title-wrap">
                                                    <div class="perm-card-icon perm-icon-lab">
                                                        <i class="fa-solid fa-flask-vial"></i>
                                                    </div>
                                                    <div>
                                                        <span class="perm-card-title">Laboratory Module</span>
                                                    </div>
                                                </div>
                                                <label class="switch-wrap" title="Toggle Laboratory Module">
                                                    <input type="checkbox" name="enable_laboratory" id="switch_lab" value="1" {{ $firstAdmin->enable_laboratory ? 'checked' : '' }} onchange="handleTogglePermission('enable_laboratory', this.checked)">
                                                    <span class="switch-slider"></span>
                                                </label>
                                            </div>
                                            <p class="perm-card-desc">
                                                Enable Laboratory testing, specimen tracking, pathology reports, and diagnostics management. Allows laboratory admin dashboard login.
                                            </p>
                                        </div>
                                        <div>
                                            <span class="perm-status-indicator {{ $firstAdmin->enable_laboratory ? 'enabled' : 'disabled' }}" id="status_text_lab">
                                                <i class="fa-solid {{ $firstAdmin->enable_laboratory ? 'fa-check' : 'fa-xmark' }}"></i>
                                                <span>{{ $firstAdmin->enable_laboratory ? 'Enabled' : 'Disabled' }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- 2. MULTI-BRANCH PERMISSION CARD -->
                                    <div class="perm-card {{ $firstAdmin->enable_multi_branch ? 'active' : '' }}" id="card_perm_branch">
                                        <div>
                                            <div class="perm-card-header">
                                                <div class="perm-card-title-wrap">
                                                    <div class="perm-card-icon perm-icon-branch">
                                                        <i class="fa-solid fa-code-branch"></i>
                                                    </div>
                                                    <div>
                                                        <span class="perm-card-title">Multi-Branch System</span>
                                                    </div>
                                                </div>
                                                <label class="switch-wrap" title="Toggle Multi-Branch System">
                                                    <input type="checkbox" name="enable_multi_branch" id="switch_branch" value="1" {{ $firstAdmin->enable_multi_branch ? 'checked' : '' }} onchange="handleTogglePermission('enable_multi_branch', this.checked)">
                                                    <span class="switch-slider"></span>
                                                </label>
                                            </div>
                                            <p class="perm-card-desc">
                                                Enable adding multiple branches ("apna branch jodna") and assigning branch managers to oversee decentralized units.
                                            </p>
                                        </div>
                                        <div>
                                            <span class="perm-status-indicator {{ $firstAdmin->enable_multi_branch ? 'enabled' : 'disabled' }}" id="status_text_branch">
                                                <i class="fa-solid {{ $firstAdmin->enable_multi_branch ? 'fa-check' : 'fa-xmark' }}"></i>
                                                <span>{{ $firstAdmin->enable_multi_branch ? 'Enabled' : 'Disabled' }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- 3. PHARMACY PERMISSION CARD -->
                                    <div class="perm-card {{ $firstAdmin->enable_pharmacy ? 'active' : '' }}" id="card_perm_pharm">
                                        <div>
                                            <div class="perm-card-header">
                                                <div class="perm-card-title-wrap">
                                                    <div class="perm-card-icon perm-icon-pharm">
                                                        <i class="fa-solid fa-pills"></i>
                                                    </div>
                                                    <div>
                                                        <span class="perm-card-title">Pharmacy Module</span>
                                                    </div>
                                                </div>
                                                <label class="switch-wrap" title="Toggle Pharmacy Module">
                                                    <input type="checkbox" name="enable_pharmacy" id="switch_pharm" value="1" {{ $firstAdmin->enable_pharmacy ? 'checked' : '' }} onchange="handleTogglePermission('enable_pharmacy', this.checked)">
                                                    <span class="switch-slider"></span>
                                                </label>
                                            </div>
                                            <p class="perm-card-desc">
                                                Enable pharmacy medicine dispensing, formulation inventory tracking, prescription orders, and billing operations.
                                            </p>
                                        </div>
                                        <div>
                                            <span class="perm-status-indicator {{ $firstAdmin->enable_pharmacy ? 'enabled' : 'disabled' }}" id="status_text_pharm">
                                                <i class="fa-solid {{ $firstAdmin->enable_pharmacy ? 'fa-check' : 'fa-xmark' }}"></i>
                                                <span>{{ $firstAdmin->enable_pharmacy ? 'Enabled' : 'Disabled' }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-footer-actions">
                                    <div class="panel-footer-hint">
                                        <i class="fa-solid fa-circle-info" style="color: #4f46e5;"></i>
                                        <span>Switches update instantly via AJAX. You can also click Save Permissions.</span>
                                    </div>
                                    <button type="submit" class="btn-save-all">
                                        <i class="fa-solid fa-floppy-disk"></i> Save Permissions
                                    </button>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="empty-selected-state">
                            <i class="fa-solid fa-hospital-user"></i>
                            <h4>No Admin Selected</h4>
                            <p>Register or select an admin to configure permissions.</p>
                        </div>
                    @endif
                </div>

                <!-- Empty placeholder when tab is closed -->
                <div class="table-card col-12 col-md-12 col-sm-12" id="emptyTabPlaceholder" style="display: none; padding: 28px; text-align: center; border: 2px dashed #cbd5e1; background: #ffffff; border-radius: 16px;">
                    <div style="font-size: 28px; color: #6366f1; margin-bottom: 8px;">
                        <i class="fa-solid fa-arrow-pointer"></i>
                    </div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Click on Any Hospital Name Above</h3>
                    <p style="font-size: 13px; color: #64748b; margin: 0;">Clicking any hospital or facility name from the list above will open its module permissions tab right here.</p>
                </div>
            </div>
        </main>
    </div>

    <!-- ------------------ JAVASCRIPT ------------------ -->
    <script>
        const currentRoleSlug = '{{ Auth::user()->role_slug ?? "superadmin" }}';
        let currentSelectedAdminId = {{ $firstAdmin ? $firstAdmin->id : 'null' }};
        let activeMedTypeFilter = 'all';

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('collapsed');
        }

        function toggleMasterSubmenu(element) {
            const parent = element.closest('.nav-item-dropdown');
            parent.classList.toggle('open');
        }

        function toggleProfileDropdown(e) {
            if (e) e.stopPropagation();
            const menu = document.getElementById('profileDropdownMenu');
            menu.classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('profileDropdownMenu');
            if (menu && !e.target.closest('.user-dropdown-wrap')) {
                menu.classList.remove('show');
            }
        });

        // Filter Admins by Search Text
        function filterAdmins(query) {
            const term = (query || '').toLowerCase().trim();
            const rows = document.querySelectorAll('.admin-row-item');
            let visibleCount = 0;

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const code = row.getAttribute('data-code') || '';
                const email = row.getAttribute('data-email') || '';
                const city = row.getAttribute('data-city') || '';
                const medtype = row.getAttribute('data-medtype') || '';

                const matchesSearch = term === '' || name.includes(term) || code.includes(term) || email.includes(term) || city.includes(term);
                const matchesType = (activeMedTypeFilter === 'all') || (medtype === activeMedTypeFilter);

                if (matchesSearch && matchesType) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countBubble = document.getElementById('adminCountBubble');
            if (countBubble) countBubble.innerText = visibleCount;
        }

        // Filter Admins by Medical Type Dropdown
        function filterByMedType(type) {
            activeMedTypeFilter = (type || 'all').toLowerCase();

            const selectEl = document.getElementById('medTypeSelect');
            if (selectEl && selectEl.value !== activeMedTypeFilter) {
                selectEl.value = activeMedTypeFilter;
            }

            const searchVal = (document.getElementById('adminSearchInput') || {}).value || '';
            filterAdmins(searchVal);
        }

        // Select Admin and Open Permission Tab Below
        function selectAndOpenAdmin(adminId, shouldScroll = true) {
            selectAdmin(adminId);
            const panel = document.getElementById('permissionPanel');
            const placeholder = document.getElementById('emptyTabPlaceholder');
            if (panel) {
                panel.style.display = 'block';
            }
            if (placeholder) {
                placeholder.style.display = 'none';
            }
            if (shouldScroll && panel) {
                panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }

        // Close Permission Tab
        function closePermissionTab() {
            const panel = document.getElementById('permissionPanel');
            const placeholder = document.getElementById('emptyTabPlaceholder');
            if (panel) {
                panel.style.display = 'none';
            }
            if (placeholder) {
                placeholder.style.display = 'block';
            }
            document.querySelectorAll('.admin-row-item').forEach(r => r.classList.remove('selected-row'));
        }

        // Select Admin from Table to Populate Permission Tab
        function selectAdmin(adminId) {
            currentSelectedAdminId = adminId;
            const row = document.getElementById('admin-row-' + adminId);
            if (!row) return;

            // Update row active state
            document.querySelectorAll('.admin-row-item').forEach(r => r.classList.remove('selected-row'));
            row.classList.add('selected-row');

            // Extract data from attributes
            const name = row.getAttribute('data-raw-name');
            const code = row.getAttribute('data-raw-code');
            const medType = row.getAttribute('data-raw-medtype') || 'Hospital';
            const city = row.getAttribute('data-raw-city') || 'N/A';
            const labPerm = row.getAttribute('data-lab') === '1';
            const branchPerm = row.getAttribute('data-branch') === '1';
            const pharmPerm = row.getAttribute('data-pharm') === '1';

            // Update Header in Bottom Section
            const panelAdminName = document.getElementById('panelAdminName');
            if (panelAdminName) panelAdminName.innerText = name;

            const panelAdminMedType = document.getElementById('panelAdminMedType');
            if (panelAdminMedType) panelAdminMedType.innerText = medType;

            const panelAdminSub = document.getElementById('panelAdminSub');
            if (panelAdminSub) panelAdminSub.innerHTML = `${code} • <span id="panelAdminMedType">${medType}</span> • <span id="panelAdminLocation"><i class="fa-solid fa-location-dot" style="font-size: 11px;"></i> ${city}</span>`;

            const iconEl = document.getElementById('panelHeaderIcon');
            if (iconEl) {
                if (medType === 'Laboratory') {
                    iconEl.innerHTML = '<i class="fa-solid fa-flask-vial"></i>';
                } else if (medType === 'Pharmacy') {
                    iconEl.innerHTML = '<i class="fa-solid fa-pills"></i>';
                } else {
                    iconEl.innerHTML = '<i class="fa-solid fa-hospital"></i>';
                }
            }

            // Update Form Action URL
            const form = document.getElementById('savePermissionsForm');
            if (form) {
                form.action = `/${currentRoleSlug}/admin-permission/${adminId}`;
            }

            const currentAdminInput = document.getElementById('currentAdminId');
            if (currentAdminInput) currentAdminInput.value = adminId;

            // Update Switches
            updateSwitchState('lab', labPerm);
            updateSwitchState('branch', branchPerm);
            updateSwitchState('pharm', pharmPerm);
        }

        function updateSwitchState(key, isChecked) {
            const sw = document.getElementById('switch_' + key);
            const card = document.getElementById('card_perm_' + key);
            const statusText = document.getElementById('status_text_' + key);

            if (sw) sw.checked = isChecked;

            if (card) {
                if (isChecked) card.classList.add('active');
                else card.classList.remove('active');
            }

            if (statusText) {
                statusText.className = 'perm-status-indicator ' + (isChecked ? 'enabled' : 'disabled');
                statusText.innerHTML = `<i class="fa-solid ${isChecked ? 'fa-check' : 'fa-xmark'}"></i> <span>${isChecked ? 'Enabled' : 'Disabled'}</span>`;
            }
        }

        // Fast AJAX Toggle Permission
        function handleTogglePermission(permissionKey, isChecked) {
            if (!currentSelectedAdminId) return;

            const keyShort = permissionKey === 'enable_laboratory' ? 'lab' : (permissionKey === 'enable_multi_branch' ? 'branch' : 'pharm');
            updateSwitchState(keyShort, isChecked);

            fetch(`/${currentRoleSlug}/admin-permission/${currentSelectedAdminId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    permission: permissionKey
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Permission updated successfully!');
                    
                    // Update data attribute on row
                    const row = document.getElementById('admin-row-' + currentSelectedAdminId);
                    if (row) {
                        row.setAttribute(keyShort === 'lab' ? 'data-lab' : (keyShort === 'branch' ? 'data-branch' : 'data-pharm'), data.value ? '1' : '0');

                        // Update mini tag in table row
                        if (keyShort === 'lab') {
                            const miniLab = document.getElementById('mini-lab-' + currentSelectedAdminId);
                            if (miniLab) {
                                miniLab.className = 'perm-tag ' + (data.value ? 'on' : 'off');
                                miniLab.innerHTML = `<i class="fa-solid fa-flask-vial"></i> Lab: ${data.value ? 'ON' : 'OFF'}`;
                            }
                        } else if (keyShort === 'branch') {
                            const miniBranch = document.getElementById('mini-branch-' + currentSelectedAdminId);
                            if (miniBranch) {
                                miniBranch.className = 'perm-tag ' + (data.value ? 'on' : 'off');
                                miniBranch.innerHTML = `<i class="fa-solid fa-code-branch"></i> Multi-Branch: ${data.value ? 'ON' : 'OFF'}`;
                            }
                        }
                    }
                } else {
                    alert(data.message || 'Error toggling permission');
                    // Revert
                    updateSwitchState(keyShort, !isChecked);
                }
            })
            .catch(err => {
                console.error(err);
                updateSwitchState(keyShort, !isChecked);
            });
        }

        function showToast(message) {
            const toast = document.getElementById('ajaxToastMsg');
            const text = document.getElementById('toastText');
            if (toast && text) {
                text.innerText = message;
                toast.style.display = 'flex';
                setTimeout(() => {
                    toast.style.display = 'none';
                }, 3500);
            }
        }
    </script>
    @include('partials.validation_popup')
</body>
</html>
