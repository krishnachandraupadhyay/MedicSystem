<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Categories - Laboratory System</title>
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
            color: #1e293b;
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

        /* USER DROPDOWN */
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
        .user-profile-btn.active .chevron-icon { transform: rotate(180deg); }

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

        /* ------------------ CONTENT AREA ------------------ */
        .content-area { padding: 32px; flex: 1; }

        .page-header-row {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px; flex-wrap: wrap; gap: 16px;
        }
        .page-heading h1 { font-size: 24px; font-weight: 800; color: #0f172a; letter-spacing: -0.4px; }
        .page-heading p { font-size: 13.5px; color: #64748b; margin-top: 4px; }

        .header-buttons { display: flex; align-items: center; gap: 10px; }

        .btn-primary-add {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: #ffffff; border: none; padding: 11px 22px; border-radius: 12px;
            font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex;
            align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            transition: all 0.2s ease; text-decoration: none;
        }
        .btn-primary-add:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4); }

        .btn-secondary-link {
            background: #ffffff; color: #475569; border: 1px solid #e2e8f0; padding: 11px 18px;
            border-radius: 12px; font-size: 13.5px; font-weight: 700; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px; text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-secondary-link:hover { background: #f8fafc; color: #0f172a; border-color: #cbd5e1; }

        /* STAT CARDS */
        .stats-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 18px; margin-bottom: 26px;
        }
        .stat-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 16px;
            padding: 20px; display: flex; align-items: center; gap: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
        }
        .stat-icon.purple { background: #eef2ff; color: #4f46e5; }
        .stat-icon.green { background: #ecfdf5; color: #059669; }
        .stat-icon.blue { background: #eff6ff; color: #2563eb; }
        .stat-icon.orange { background: #fff7ed; color: #ea580c; }
        .stat-meta h3 { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.1; }
        .stat-meta p { font-size: 12px; font-weight: 600; color: #64748b; margin-top: 3px; }

        /* FILTER BAR */
        .filter-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 16px;
            padding: 16px 20px; margin-bottom: 22px; display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 14px;
        }
        .filter-left { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; flex: 1; }
        .search-wrap { position: relative; min-width: 260px; flex: 1; }
        .search-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px; }
        .search-input {
            width: 100%; height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px 0 38px; font-size: 13.5px; color: #1e293b; outline: none;
        }
        .search-input:focus { background: #ffffff; border-color: #6366f1; }

        .select-filter {
            height: 40px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px;
            padding: 0 14px; font-size: 13px; color: #1e293b; outline: none; font-weight: 600; cursor: pointer;
        }
        .select-filter:focus { background: #ffffff; border-color: #6366f1; }

        /* TABLE CARD */
        .table-card {
            background: #ffffff; border: 1px solid #eef2f7; border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02); overflow: hidden;
        }
        .card-topbar {
            padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex;
            align-items: center; justify-content: space-between;
        }
        .card-title-badge { display: flex; align-items: center; gap: 10px; }
        .card-title-badge h2 { font-size: 16px; font-weight: 800; color: #1e293b; }
        .count-bubble { background: #eef2ff; color: #4f46e5; font-size: 12px; font-weight: 700; padding: 3px 10px; border-radius: 20px; }

        .table-wrap { overflow-x: auto; }
        .data-table { width: 100%; border-collapse: collapse; text-align: left; }
        .data-table th {
            background: #f8fafc; color: #475569; font-size: 11.5px; font-weight: 700;
            letter-spacing: 0.6px; text-transform: uppercase; padding: 13px 20px; border-bottom: 1px solid #eef2f7;
        }
        .data-table td { padding: 15px 20px; border-bottom: 1px solid #f1f5f9; font-size: 13.5px; color: #334155; vertical-align: middle; }
        .data-table tr:hover td { background-color: #fafbfc; }

        .cat-icon-box {
            width: 38px; height: 38px; border-radius: 10px; background: #eef2ff; color: #4f46e5;
            display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
        }
        .cat-info-cell { display: flex; align-items: center; gap: 12px; }
        .cat-name-text { font-size: 14px; font-weight: 700; color: #0f172a; }
        .cat-desc-sub { font-size: 12px; color: #64748b; margin-top: 2px; max-width: 380px; }

        .badge-code { background: #f1f5f9; color: #475569; padding: 3px 8px; border-radius: 6px; font-weight: 700; font-size: 11.5px; }
        .badge-tests-count { background: #ecfdf5; color: #047857; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; }
        .badge-scope { background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; }

        .badge-status { font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; }
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
            background: #ffffff; width: 100%; max-width: 540px; border-radius: 16px; overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15); animation: zoomIn 0.2s ease;
        }
        @keyframes zoomIn { from { transform: scale(0.96); opacity: 0; } to { transform: scale(1); opacity: 1; } }

        .modal-head { padding: 18px 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .modal-head h3 { font-size: 17px; font-weight: 800; color: #0f172a; }
        .close-x-btn { background: none; border: none; font-size: 22px; color: #94a3b8; cursor: pointer; }
        .modal-content-pad { padding: 22px 24px; }
        .grid-2col { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field-item { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
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
        .btn-modal-submit:hover { background: #4338ca; }

        /* TOAST */
        .toast-msg {
            background: #ecfdf5; border-left: 4px solid #10b981; color: #065f46;
            padding: 14px 18px; border-radius: 10px; font-size: 13.5px; font-weight: 600;
            margin-bottom: 22px; display: flex; align-items: center; justify-content: space-between;
        }
        .toast-error { background: #fef2f2; border-left-color: #ef4444; color: #991b1b; }
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

            <!-- PAGE HEADER -->
            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Test Categories Management</h1>
                    <p>Organize, classify, and manage diagnostic medical categories (e.g., Biochemistry, Hematology, Pathology).</p>
                </div>

                <div class="header-buttons">
                    @if($canCreate)
                        <button class="btn-primary-add" onclick="openAddCategoryModal()">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Category</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- WORKFLOW STEPS BANNER -->
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 18px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                    <span style="font-weight: 800; font-size: 13px; color: #1e293b;"><i class="fa-solid fa-diagram-project" style="color: #059669;"></i> Standard Workflow:</span>
                    <div style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #64748b; font-weight: 600; flex-wrap: wrap;">
                        <span style="background: #ecfdf5; color: #047857; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #a7f3d0;">Step 1: Category Create</span>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <a href="{{ url($rSlug . '/tests') }}" style="text-decoration: none; background: #eff6ff; color: #1d4ed8; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #bfdbfe;" title="Step 2: Lab Tests">Step 2: Test Create</a>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <a href="{{ url($rSlug . '/test-fees') }}" style="text-decoration: none; background: #f0fdf4; color: #15803d; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #bbf7d0;" title="Step 3: Test Fees & Pricing">Step 3: Fees &amp; Pricing</a>
                        <i class="fa-solid fa-arrow-right" style="font-size: 10px; color: #94a3b8;"></i>
                        <span style="background: #fef3c7; color: #b45309; padding: 3px 10px; border-radius: 20px; font-weight: 700; border: 1px solid #fde68a;">Step 4: Admin Verification</span>
                    </div>
                </div>
                <div style="font-size: 12px; color: #64748b; font-weight: 600;">
                    <i class="fa-solid fa-shield-halved" style="color: #059669;"></i> Create categories first, then add tests and pricing under them.
                </div>
            </div>

            <!-- FILTERS -->
            <form method="GET" action="{{ url($rSlug . '/test-categories') }}" class="filter-card">
                <div class="filter-left">
                    <div class="search-wrap">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="search" class="search-input" value="{{ request('search') }}" placeholder="Search category by name or code...">
                    </div>
                    @if(!Auth::user()->is_branch_manager && $branches->count() > 0)
                        <select name="branch_id" class="select-filter" onchange="this.form.submit()">
                            <option value="">🏢 All Lab Branches</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ request('branch_id') == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                    @endif
                    <select name="status" class="select-filter" onchange="this.form.submit()">
                        <option value="">⚡ All Statuses</option>
                        <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Pending Verification" {{ request('status') == 'Pending Verification' ? 'selected' : '' }}>Pending Verification</option>
                        <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn-secondary-link" style="padding: 9px 16px;">
                        <i class="fa-solid fa-filter"></i> Apply Filter
                    </button>
                    @if(request('search') || (!Auth::user()->is_branch_manager && request('branch_id')) || request('status'))
                        <a href="{{ url($rSlug . '/test-categories') }}" class="btn-secondary-link" style="padding: 9px 14px; color: #ef4444;">
                            <i class="fa-solid fa-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- CATEGORIES TABLE -->
            <div class="table-card">
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Diagnostic Categories</h2>
                        <span class="count-bubble" id="catCount">{{ $categories->count() }}</span>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>Category Name &amp; Description</th>
                                <th>Code</th>
                                <th>Tests Count</th>
                                <th>Branch Scope</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $index => $cat)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="cat-info-cell">
                                            <div class="cat-icon-box"><i class="fa-solid fa-folder"></i></div>
                                            <div>
                                                <div class="cat-name-text">{{ $cat->name }}</div>
                                                <div class="cat-desc-sub">{{ $cat->description ?: 'No description provided.' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge-code">{{ $cat->code ?: 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge-tests-count">
                                            <i class="fa-solid fa-vials"></i> {{ $cat->tests_count }} Tests
                                        </span>
                                    </td>
                                    <td>
                                        @if($cat->branch)
                                            <span class="badge-scope"><i class="fa-solid fa-building"></i> {{ $cat->branch->name }}</span>
                                        @else
                                            <span class="badge-scope" style="background: #eef2ff; color: #4f46e5;"><i class="fa-solid fa-building-user"></i> All Lab Branches</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($cat->status === 'Pending Verification')
                                            <span style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding: 4px 10px; border-radius: 20px; font-size: 11.5px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px;">
                                                <i class="fa-solid fa-clock"></i> Pending Verification
                                            </span>
                                        @elseif(strtolower($cat->status) === 'active')
                                            <span class="badge-status active">
                                                <i class="fa-solid fa-circle" style="font-size: 7px;"></i> Active
                                            </span>
                                        @else
                                            <span class="badge-status inactive">
                                                <i class="fa-solid fa-circle" style="font-size: 7px;"></i> {{ $cat->status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-btns" style="justify-content: flex-end; white-space: nowrap;">
                                            @if($cat->status === 'Pending Verification' && (Auth::user()->is_admin || in_array(Auth::user()->role, ['Super Admin', 'Laboratory Admin', 'Admin'])))
                                                <form action="{{ url($rSlug . '/test-categories/' . $cat->id . '/verify') }}" method="POST" style="display:inline;" onsubmit="return confirm('Verify & approve category {{ $cat->name }}?')">
                                                    @csrf
                                                    <button type="submit" class="btn-action-icon" title="Verify &amp; Approve Category" style="color: #047857; background: #ecfdf5; border: 1px solid #a7f3d0;">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($canEdit)
                                                <button class="btn-action-icon" title="Edit Category" onclick='openEditCategoryModal(@json($cat))'>
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                            @endif
                                            @if($canDelete)
                                                <form action="{{ url($rSlug . '/test-categories/' . $cat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this category? Tests in this category might become unclassified.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action-icon delete" title="Delete Category">
                                                        <i class="fa-solid fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 48px 20px; color: #94a3b8;">
                                        <i class="fa-solid fa-folder-open" style="font-size: 38px; color: #cbd5e1; margin-bottom: 12px; display: block;"></i>
                                        <p style="font-weight: 700; font-size: 15px; color: #64748b;">No Test Categories Found</p>
                                        <p style="font-size: 13px; margin-top: 4px;">Start by creating your first category like "Biochemistry" or "Hematology".</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: ADD CATEGORY -->
    <div class="modal-backdrop" id="addCategoryModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add Test Category</h3>
                <button type="button" class="close-x-btn" onclick="closeAddCategoryModal()">&times;</button>
            </div>
            <form action="{{ url($rSlug . '/test-categories') }}" method="POST">
                @csrf
                <div class="modal-content-pad">
                    <div class="field-item">
                        <label>Category Name *</label>
                        <input type="text" name="name" class="field-input" required placeholder="e.g. Hematology, Biochemistry">
                    </div>
                    <div class="grid-2col">
                        <div class="field-item">
                            <label>Category Code</label>
                            <input type="text" name="code" class="field-input" placeholder="e.g. HEM, BIO">
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    @if(!Auth::user()->is_branch_manager && $branches->count() > 0)
                        <div class="field-item">
                            <label>Branch Assignment</label>
                            <select name="branch_id" class="field-input">
                                <option value="">🏢 All Lab Branches</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="margin-top: 6px; margin-bottom: 14px;">
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 12.5px; font-weight: 600; color: #475569; cursor: pointer;">
                                <input type="checkbox" name="apply_to_all_branches" value="1" checked style="width: 16px; height: 16px;">
                                Auto-replicate category across all active branches
                            </label>
                        </div>
                    @elseif(Auth::user()->is_branch_manager && Auth::user()->branch)
                        <input type="hidden" name="branch_id" value="{{ Auth::user()->branch->id }}">
                    @endif

                    <div class="field-item">
                        <label>Description</label>
                        <textarea name="description" class="field-input" style="height: 70px; padding: 10px;" placeholder="Brief details about diagnostic tests in this category..."></textarea>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeAddCategoryModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Save Category</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT CATEGORY -->
    <div class="modal-backdrop" id="editCategoryModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Test Category</h3>
                <button type="button" class="close-x-btn" onclick="closeEditCategoryModal()">&times;</button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content-pad">
                    <div class="field-item">
                        <label>Category Name *</label>
                        <input type="text" name="name" id="edit_cat_name" class="field-input" required>
                    </div>
                    <div class="grid-2col">
                        <div class="field-item">
                            <label>Category Code</label>
                            <input type="text" name="code" id="edit_cat_code" class="field-input">
                        </div>
                        <div class="field-item">
                            <label>Status</label>
                            <select name="status" id="edit_cat_status" class="field-input">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    @if(!Auth::user()->is_branch_manager && $branches->count() > 0)
                        <div class="field-item">
                            <label>Branch Assignment</label>
                            <select name="branch_id" id="edit_cat_branch" class="field-input">
                                <option value="">🏢 All Lab Branches</option>
                                @foreach($branches as $b)
                                    <option value="{{ $b->id }}">{{ $b->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif(Auth::user()->is_branch_manager && Auth::user()->branch)
                        <input type="hidden" name="branch_id" id="edit_cat_branch" value="{{ Auth::user()->branch->id }}">
                    @endif

                    <div class="field-item">
                        <label>Description</label>
                        <textarea name="description" id="edit_cat_desc" class="field-input" style="height: 70px; padding: 10px;"></textarea>
                    </div>
                </div>
                <div class="modal-action-bar">
                    <button type="button" class="btn-modal-cancel" onclick="closeEditCategoryModal()">Cancel</button>
                    <button type="submit" class="btn-modal-submit">Update Category</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }

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

        window.addEventListener('click', function(e) {
            const wrap = document.getElementById('userDropdownWrap');
            const menu = document.getElementById('profileDropdownMenu');
            const btn = document.getElementById('userProfileBtn');
            if (menu && wrap && !wrap.contains(e.target)) {
                menu.classList.remove('show');
                if (btn) btn.classList.remove('active');
            }
        });

        function openAddCategoryModal() { document.getElementById('addCategoryModal').style.display = 'flex'; }
        function closeAddCategoryModal() { document.getElementById('addCategoryModal').style.display = 'none'; }

        function openEditCategoryModal(cat) {
            const form = document.getElementById('editCategoryForm');
            form.action = `{{ url($rSlug . '/test-categories') }}/${cat.id}`;
            document.getElementById('edit_cat_name').value = cat.name || '';
            document.getElementById('edit_cat_code').value = cat.code || '';
            document.getElementById('edit_cat_status').value = cat.status || 'Active';
            document.getElementById('edit_cat_desc').value = cat.description || '';
            const branchSel = document.getElementById('edit_cat_branch');
            if (branchSel) branchSel.value = cat.branch_id || '';
            document.getElementById('editCategoryModal').style.display = 'flex';
        }
        function closeEditCategoryModal() { document.getElementById('editCategoryModal').style.display = 'none'; }
    </script>
    @include('partials.validation_popup')
</body>
</html>
