<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RK Hospital - Portal Login</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 960px;
            min-height: 560px;
            background: #ffffff;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.15), 0 0 1px 1px rgba(0,0,0,0.03);
            display: flex;
            position: relative;
        }

        /* LEFT BRAND PANEL */
        .brand-panel {
            flex: 1.1;
            background: linear-gradient(145deg, #1d4ed8 0%, #2563eb 45%, #1e40af 100%);
            color: #ffffff;
            position: relative;
            padding: 50px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            z-index: 1;
        }

        /* Decorative layered waves */
        .wave-decoration {
            position: absolute;
            top: 0;
            right: -2px;
            width: 120px;
            height: 100%;
            pointer-events: none;
            z-index: 2;
        }

        .brand-top-text {
            font-size: 24px;
            font-weight: 600;
            letter-spacing: -0.5px;
            color: rgba(255, 255, 255, 0.95);
            text-align: center;
            margin-bottom: 25px;
        }

        .brand-hero {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            z-index: 3;
        }

        .brand-logo-circle {
            width: 105px;
            height: 105px;
            background: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.18);
            transition: transform 0.3s ease;
        }

        .brand-logo-circle:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .brand-logo-circle i {
            font-size: 46px;
            color: #2563eb;
        }

        .brand-name {
            font-size: 28px;
            font-weight: 800;
            letter-spacing: -0.5px;
            margin-bottom: 14px;
            color: #ffffff;
        }

        .brand-desc {
            font-size: 14px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.88);
            max-width: 320px;
            font-weight: 400;
        }

        /* RIGHT FORM PANEL */
        .form-panel {
            flex: 1.2;
            background: #ffffff;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 3;
        }

        .form-header {
            margin-bottom: 28px;
            text-align: left;
        }

        .form-title {
            font-size: 28px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }

        .form-subtitle {
            font-size: 14px;
            color: #64748b;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fee2e2;
        }

        .alert-success {
            background: #ecfdf5;
            color: #059669;
            border: 1px solid #d1fae5;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            height: 50px;
            padding: 0 18px;
            font-size: 14.5px;
            color: #1e293b;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            outline: none;
            background: #ffffff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .form-control::placeholder {
            color: #94a3b8;
            font-size: 14px;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
            font-size: 13.5px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .checkbox-label input {
            width: 17px;
            height: 17px;
            accent-color: #2563eb;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-submit {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #1d4ed8 0%, #2563eb 100%);
            color: #ffffff;
            border: none;
            border-radius: 14px;
            font-size: 15.5px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(37, 99, 235, 0.5);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* RESPONSIVE DESIGN */
        @media (max-width: 860px) {
            .auth-container {
                flex-direction: column;
                max-width: 440px;
                min-height: auto;
                border-radius: 28px;
            }

            .brand-panel {
                padding: 40px 25px 45px;
                border-radius: 28px 28px 0 0;
            }

            .wave-decoration {
                display: none;
            }

            .form-panel {
                padding: 35px 25px 40px;
            }
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <!-- LEFT PANEL (BLUE HERO) -->
        <div class="brand-panel">
            <!-- Organic wave SVG separating panels -->
            <svg class="wave-decoration" viewBox="0 0 100 800" preserveAspectRatio="none">
                <path d="M0,0 C40,120 70,250 30,400 C-10,550 60,700 0,800 L100,800 L100,0 Z" fill="#ffffff" opacity="0.15"></path>
                <path d="M20,0 C60,160 85,280 45,430 C15,570 75,720 20,800 L100,800 L100,0 Z" fill="#ffffff" opacity="0.3"></path>
                <path d="M45,0 C85,180 95,310 65,460 C35,600 85,730 50,800 L100,800 L100,0 Z" fill="#ffffff"></path>
            </svg>

            <div class="brand-top-text">Welcome to</div>

            <div class="brand-hero">
                <div class="brand-logo-circle">
                    <!-- Rocket / Hospital emblem -->
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <h1 class="brand-name">RK Hospital</h1>
                <p class="brand-desc">
                    Comprehensive hospital administration platform. Manage doctor rosters, patient records, departments, and administrative credentials securely.
                </p>
            </div>
        </div>

        <!-- RIGHT PANEL (FORM) -->
        <div class="form-panel">
            <div class="form-header">
                <h2 class="form-title">Sign In to account</h2>
                <p class="form-subtitle">Enter your administrator credentials to continue</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <div>{{ $errors->first() }}</div>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            @if (session('info'))
                <div class="alert alert-success" style="background:#eff6ff; color:#2563eb; border-color:#dbeafe;">
                    <i class="fa-solid fa-info-circle"></i>
                    <div>{{ session('info') }}</div>
                </div>
            @endif

            <!-- SIGN IN FORM -->
            <form action="{{ route('login.post') }}" method="POST" id="signInForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" id="loginEmail" class="form-control" placeholder="Enter email" value="{{ old('email', 'admin@gmail.com') }}" required autofocus>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Enter Password" value="admin123" required>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember" checked>
                        <span>Remember my session</span>
                    </label>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Sign In</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>

</body>
</html>
