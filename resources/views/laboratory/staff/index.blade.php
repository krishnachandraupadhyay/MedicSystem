<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Staff &amp; Team Management - {{ $hospital->name ?? 'Diagnostic Lab' }}</title>
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

        .sidebar.collapsed { width: 78px; }
        .sidebar.collapsed .brand-info,
        .sidebar.collapsed .menu-heading,
        .sidebar.collapsed .nav-title { display: none; }
        .sidebar.collapsed .brand-block { justify-content: center; padding: 24px 0; }
        .sidebar.collapsed .nav-link { justify-content: center; padding: 12px 0; margin: 4px 10px; }
        .sidebar.collapsed .nav-link i { margin-right: 0; font-size: 18px; }

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

        .content-area {
            padding: 32px;
            flex: 1;
        }

        .toast-msg {
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 13.5px;
            font-weight: 600;
        }
        .toast-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .toast-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

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

        .btn-action-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
            color: #ffffff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 13.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(79, 70, 229, 0.3);
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-action-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(79, 70, 229, 0.4);
        }

        /* TABLE CARD */
        .table-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 2px 14px rgba(15, 23, 42, 0.03);
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .card-topbar {
            padding: 20px 24px;
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
            gap: 10px;
        }

        .card-title-badge h2 {
            font-size: 17px;
            font-weight: 800;
            color: #0f172a;
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

        .role-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #eef2ff;
            color: #4f46e5;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .branch-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f1f5f9;
            color: #475569;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
        }
        .branch-pill.all {
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

        /* MODALS */
        .modal-backdrop {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
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
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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

        .close-btn { background: none; border: none; font-size: 22px; color: #94a3b8; cursor: pointer; }
        .modal-body { padding: 24px 28px; }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field-group { display: flex; flex-direction: column; gap: 6px; }
        .field-group label { font-size: 13px; font-weight: 700; color: #334155; }
        .field-control {
            padding: 10px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            font-size: 13.5px;
            outline: none;
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

            <div class="page-header-row">
                <div class="page-heading">
                    <h1>Lab Staff &amp; Team Members</h1>
                    <p>Manage pathologists, technicians, phlebotomists, and facility staff for <b>{{ $hospital->name ?? 'Diagnostic Lab' }}</b></p>
                </div>

                @if($canCreate)
                    <button type="button" class="btn-action-primary" onclick="openAddStaffModal()">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Add Staff Member</span>
                    </button>
                @endif
            </div>

            <div class="table-card">
                <div class="card-topbar">
                    <div class="card-title-badge">
                        <h2>Lab Team &amp; Technicians</h2>
                        <span class="badge-counter">{{ count($staffMembers) }}</span>
                    </div>

                    <form action="{{ url($rSlug . '/staff') }}" method="GET" style="display:flex; gap:10px;">
                        @if(!Auth::user()->is_branch_manager)
                        <select name="branch_id" onchange="this.form.submit()" style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 13px;">
                            <option value="">🏢 All Lab Branches</option>
                            @foreach($branches as $b)
                                <option value="{{ $b->id }}" {{ $selectedBranchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search staff name, code..." style="padding: 8px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 13px;">
                    </form>
                </div>

                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>STAFF NAME</th>
                                <th>DESIGNATION &amp; DEGREE</th>
                                <th>ASSIGNED ROLE &amp; CATEGORY</th>
                                <th>BRANCH</th>
                                <th>CONTACT</th>
                                <th>STATUS</th>
                                <th style="text-align: right; width: 100px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($staffMembers as $idx => $st)
                                <tr>
                                    <td style="color: #64748b; font-weight: 600;">{{ $idx + 1 }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: #0f172a;">{{ $st->name }}</div>
                                        @if($st->employee_id)
                                            <span style="font-size: 11.5px; color: #64748b;">EMP ID: {{ $st->employee_id }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: #334155;">{{ $st->designation ?? 'Lab Staff' }}</div>
                                        @if($st->qualification)
                                            <span style="font-size: 11px; color: #64748b;">{{ $st->qualification }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($st->role)
                                            <span class="role-pill"><i class="fa-solid fa-shield-halved"></i> {{ $st->role->name }}</span>
                                        @else
                                            <span style="color: #94a3b8; font-size: 12px;">-- Standard Staff --</span>
                                        @endif
                                        @if($st->category)
                                            <div style="margin-top: 4px;">
                                                <span style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd; font-size: 10.5px; font-weight: 700; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="fa-solid fa-flask" style="font-size: 9px;"></i> {{ $st->category->name }}
                                                </span>
                                            </div>
                                        @elseif($st->role && (str_contains(strtolower($st->role->name), 'front') || str_contains(strtolower($st->role->name), 'reception')))
                                            <div style="margin-top: 4px;">
                                                <span style="background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                                    <i class="fa-solid fa-headset" style="font-size: 9.5px;"></i> Front Desk / Reception
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($st->branch)
                                            <span class="branch-pill"><i class="fa-solid fa-building"></i> {{ $st->branch->name }}</span>
                                        @else
                                            <span class="branch-pill all"><i class="fa-solid fa-building-user"></i> All Lab Branches</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div style="font-size: 12.5px; color: #475569;">
                                            @if($st->phone) <div><i class="fa-solid fa-phone" style="font-size: 10px;"></i> {{ $st->phone }}</div> @endif
                                            @if($st->email) <div style="color: #64748b;"><i class="fa-regular fa-envelope" style="font-size: 10px;"></i> {{ $st->email }}</div> @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-pill {{ strtolower($st->status) === 'active' ? 'active' : 'inactive' }}">{{ $st->status }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        @if($canEdit)
                                            <button type="button" class="btn-icon-action" onclick="openEditStaffModal({{ json_encode($st) }})"><i class="fa-solid fa-pen-to-square"></i></button>
                                        @endif
                                        @if($canDelete)
                                            <form action="{{ url($rSlug . '/staff/' . $st->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Remove staff member {{ $st->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-icon-action delete"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" style="text-align: center; padding: 36px; color: #64748b;">
                                        No staff members found. Click <b>+ Add Staff Member</b> to register team members.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL: ADD STAFF -->
    <div class="modal-backdrop" id="addStaffModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Add Staff Member</h3>
                <button type="button" class="close-btn" onclick="closeAddStaffModal()">&times;</button>
            </div>
            <form action="{{ url($rSlug . '/staff') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="field-group" style="grid-column: span 2;">
                            <label>Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" class="field-control" placeholder="e.g. Dr. Rajesh Kumar / Sneha Roy" required>
                        </div>
                        <div class="field-group">
                            <label>Designation</label>
                            <input type="text" name="designation" class="field-control" placeholder="e.g. Lab Technician, Phlebotomist">
                        </div>
                        <div class="field-group">
                            <label>Qualification / Degree</label>
                            <input type="text" name="qualification" class="field-control" placeholder="e.g. DMLT, B.Sc MLT, MD">
                        </div>
                        <div class="field-group">
                            <label>Assigned Custom Role</label>
                            <select name="role_id" id="add_staff_role_id" class="field-control" onchange="handleRoleCategoryChange(this, 'add')">
                                <option value="">-- No Specific Custom Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group" id="add_category_field_group">
                            <label>Test Category / Department</label>
                            <select name="test_category_id" id="add_staff_test_category_id" class="field-control">
                                <option value="">-- No Test Category Assigned --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div id="add_category_frontdesk_hint" style="font-size: 11px; color: #047857; font-weight: 600; margin-top: 4px; display: none;">
                                <i class="fa-solid fa-circle-check"></i> Test category is not required for Front Desk (Receptionist) role.
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Branch Assignment</label>
                            @if(Auth::user()->is_branch_manager && Auth::user()->branch)
                                <input type="hidden" name="branch_id" value="{{ Auth::user()->branch->id }}">
                                <input type="text" class="field-control" value="🏢 {{ Auth::user()->branch->name }}" readonly style="background: #f8fafc; font-weight: 600; color: #047857;">
                            @else
                                <select name="branch_id" class="field-control">
                                    <option value="">🏢 All Lab Branches</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field-group">
                            <label>Email Address</label>
                            <input type="email" name="email" class="field-control" placeholder="staff@example.com">
                        </div>
                        <div class="field-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" class="field-control" placeholder="9876543210">
                        </div>
                        <div class="field-group">
                            <label>Employee Code / ID</label>
                            <input type="text" name="employee_id" class="field-control" placeholder="EMP-101">
                        </div>
                        <div class="field-group">
                            <label>Status</label>
                            <select name="status" class="field-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeAddStaffModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Save Staff Member</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL: EDIT STAFF -->
    <div class="modal-backdrop" id="editStaffModal">
        <div class="modal-box">
            <div class="modal-head">
                <h3>Edit Staff Member</h3>
                <button type="button" class="close-btn" onclick="closeEditStaffModal()">&times;</button>
            </div>
            <form id="editStaffForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-grid-2">
                        <div class="field-group" style="grid-column: span 2;">
                            <label>Full Name <span style="color:#ef4444;">*</span></label>
                            <input type="text" name="name" id="edit_staff_name" class="field-control" required>
                        </div>
                        <div class="field-group">
                            <label>Designation</label>
                            <input type="text" name="designation" id="edit_staff_designation" class="field-control">
                        </div>
                        <div class="field-group">
                            <label>Qualification / Degree</label>
                            <input type="text" name="qualification" id="edit_staff_qualification" class="field-control">
                        </div>
                        <div class="field-group">
                            <label>Assigned Custom Role</label>
                            <select name="role_id" id="edit_staff_role_id" class="field-control" onchange="handleRoleCategoryChange(this, 'edit')">
                                <option value="">-- No Specific Custom Role --</option>
                                @foreach($roles as $r)
                                    <option value="{{ $r->id }}">{{ $r->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-group" id="edit_category_field_group">
                            <label>Test Category / Department</label>
                            <select name="test_category_id" id="edit_staff_test_category_id" class="field-control">
                                <option value="">-- No Test Category Assigned --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <div id="edit_category_frontdesk_hint" style="font-size: 11px; color: #047857; font-weight: 600; margin-top: 4px; display: none;">
                                <i class="fa-solid fa-circle-check"></i> Test category is not required for Front Desk (Receptionist) role.
                            </div>
                        </div>
                        <div class="field-group">
                            <label>Branch Assignment</label>
                            @if(Auth::user()->is_branch_manager && Auth::user()->branch)
                                <input type="hidden" name="branch_id" id="edit_staff_branch_id" value="{{ Auth::user()->branch->id }}">
                                <input type="text" class="field-control" value="🏢 {{ Auth::user()->branch->name }}" readonly style="background: #f8fafc; font-weight: 600; color: #047857;">
                            @else
                                <select name="branch_id" id="edit_staff_branch_id" class="field-control">
                                    <option value="">🏢 All Lab Branches</option>
                                    @foreach($branches as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="field-group">
                            <label>Email Address</label>
                            <input type="email" name="email" id="edit_staff_email" class="field-control">
                        </div>
                        <div class="field-group">
                            <label>Phone Number</label>
                            <input type="text" name="phone" id="edit_staff_phone" class="field-control">
                        </div>
                        <div class="field-group">
                            <label>Employee Code / ID</label>
                            <input type="text" name="employee_id" id="edit_staff_employee_id" class="field-control">
                        </div>
                        <div class="field-group">
                            <label>Status</label>
                            <select name="status" id="edit_staff_status" class="field-control">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn-cancel" onclick="closeEditStaffModal()">Cancel</button>
                    <button type="submit" class="btn-submit">Update Staff Member</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('collapsed'); }
        function openAddStaffModal() {
            document.getElementById('addStaffModal').style.display = 'flex';
            handleRoleCategoryChange(document.getElementById('add_staff_role_id'), 'add');
        }
        function closeAddStaffModal() { document.getElementById('addStaffModal').style.display = 'none'; }
        
        function handleRoleCategoryChange(selectElem, mode) {
            const selectedText = (selectElem.options[selectElem.selectedIndex] ? selectElem.options[selectElem.selectedIndex].text : '').toLowerCase();
            const isFrontDesk = selectedText.includes('front') || selectedText.includes('reception');
            const catSelect = document.getElementById(mode + '_staff_test_category_id');
            const hint = document.getElementById(mode + '_category_frontdesk_hint');
            
            if (isFrontDesk) {
                if (catSelect) {
                    catSelect.value = '';
                    catSelect.disabled = true;
                    catSelect.style.backgroundColor = '#f1f5f9';
                    catSelect.style.cursor = 'not-allowed';
                }
                if (hint) hint.style.display = 'block';
            } else {
                if (catSelect) {
                    catSelect.disabled = false;
                    catSelect.style.backgroundColor = '#ffffff';
                    catSelect.style.cursor = 'default';
                }
                if (hint) hint.style.display = 'none';
            }
        }

        function openEditStaffModal(st) {
            const form = document.getElementById('editStaffForm');
            form.action = `{{ url($rSlug . '/staff') }}/${st.id}`;
            document.getElementById('edit_staff_name').value = st.name || '';
            document.getElementById('edit_staff_designation').value = st.designation || '';
            document.getElementById('edit_staff_qualification').value = st.qualification || '';
            document.getElementById('edit_staff_role_id').value = st.role_id || '';
            if (document.getElementById('edit_staff_test_category_id')) {
                document.getElementById('edit_staff_test_category_id').value = st.test_category_id || '';
            }
            if (document.getElementById('edit_staff_branch_id') && document.getElementById('edit_staff_branch_id').tagName === 'SELECT') {
                document.getElementById('edit_staff_branch_id').value = st.branch_id || '';
            }
            document.getElementById('edit_staff_email').value = st.email || '';
            document.getElementById('edit_staff_phone').value = st.phone || '';
            document.getElementById('edit_staff_employee_id').value = st.employee_id || '';
            document.getElementById('edit_staff_status').value = st.status || 'Active';
            
            handleRoleCategoryChange(document.getElementById('edit_staff_role_id'), 'edit');
            document.getElementById('editStaffModal').style.display = 'flex';
        }
        function closeEditStaffModal() { document.getElementById('editStaffModal').style.display = 'none'; }

        // Topbar Profile Dropdown
        function toggleProfileDropdown(e) {
            e.stopPropagation();
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
