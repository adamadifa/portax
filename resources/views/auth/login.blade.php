<!DOCTYPE html>
<html lang="en" class="h-full">
@php
    $domain = request()->getHost();
    $companies = [
        'intirasapanganpersada.portax.site' => 'PT. INTIRASA PANGAN PERSADA',
        'asiabogordistribusi.portax.site' => 'PT. ASIA BOGOR DISTRIBUSI',
        'suburmakmurutama.portax.site' => 'PT. SUBUR MAKMUR UTAMA',
        'bantenmajujaya.portax.site' => 'PT. BANTEN MAJU JAYA',
        'suburmakmuralami.portax.site' => 'PT. SUBUR MAKMUR ALAMI',
        'garutcahayaperkasa.portax.site' => 'PT. GARUT CAHAYA PERKASA',
        'intisaricapsaicindo.portax.site' => 'PT. INTI SARI CAPSAICINDO',
        'intirasalamisejahtera.portax.site' => 'PT. INTIRASA ALAMI SEJAHTERA',
        'cakrawalapangandistribusi.portax.site' => 'PT. CAKRAWALA PANGAN DISTRIBUSI',
        'langgengkaryaabhinaya.portax.site' => 'PT. LANGGENG KARYA ABHINAYA',
        'rasautamagemilang.portax.site' => 'PT. RASA UTAMA GEMILANG',
        'pangansemarangsejahtera.portax.site' => 'PT. PANGAN SEMARANG SEJAHTERA',
        'makmuranugrahdistribusindo.portax.site' => 'PT. MAKMUR ANUGRAH DISTRIBUSINDO',
        'cahayarianggalunggung.portax.site' => 'PT. CAHAYA RIANG GALUNGGUNG',
        'makmurpermata.portax.site' => 'CV. MAKMUR PERMATA',
    ];
    $companyName = $companies[$domain] ?? 'Portax';
@endphp

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | {{ $companyName }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/tabler-icons.css') }}" />

    <style>
        :root {
            --primary: #052659;
            --primary-dark: #031a3d;
            --primary-light: #1e4a8c;
            --secondary: #3b82f6;
            --text-main: #1f2937;
            --text-muted: #6b7280;
            --bg-glass: rgba(255, 255, 255, 0.9);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #052659;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Abstract Background Circles */
        .bg-pattern {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: 
                radial-gradient(circle at 10% 20%, rgba(5, 38, 89, 0.8) 0%, transparent 100%),
                linear-gradient(rgba(255, 255, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.02) 1px, transparent 1px);
            background-size: cover, 40px 40px, 40px 40px;
        }

        /* Neon Glow Ornaments */
        .neon-glow {
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(59, 130, 246, 0.2);
            filter: blur(100px);
            border-radius: 50%;
            z-index: -1;
        }

        .bg-circle {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            z-index: -1;
            opacity: 0.4;
        }

        .circle-1 {
            width: 400px;
            height: 400px;
            background: var(--primary-light);
            top: -100px;
            right: -100px;
        }

        .circle-2 {
            width: 300px;
            height: 300px;
            background: var(--primary);
            bottom: -50px;
            left: -50px;
        }

        .circle-3 {
            width: 200px;
            height: 200px;
            background: var(--primary-dark);
            top: 20%;
            left: 10%;
        }

        .main-container {
            width: 100%;
            max-width: 1000px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            overflow: hidden;
            min-height: 600px;
            position: relative;
            z-index: 10;
        }

        /* Left Panel - Gradient Theme */
        .left-panel {
            flex: 1;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: 
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 20px 20px;
            top: 0;
            left: 0;
            z-index: 0;
        }

        .left-panel::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 30%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
            z-index: 0;
        }

        /* Removed left-panel decorative circles as requested */

        .panel-title {
            font-size: 42px;
            font-weight: 700;
            margin-bottom: 20px;
            line-height: 1.2;
            z-index: 1;
        }

        .panel-subtitle {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 40px;
            max-width: 280px;
            line-height: 1.6;
            z-index: 1;
        }

        .btn-outline {
            padding: 14px 40px;
            border: 2px solid white;
            border-radius: 30px;
            background: transparent;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
            z-index: 1;
        }

        .btn-outline:hover {
            background: white;
            color: var(--primary);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* Right Panel - Form */
        .right-panel {
            flex: 1.2;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            background: white;
        }

        .form-header { margin-bottom: 40px; }
        .form-title {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 10px;
        }
        .form-subtitle {
            font-size: 15px;
            color: var(--text-muted);
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 16px;
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 15px;
            color: var(--text-main);
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            padding: 4px;
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
            filter: brightness(1.1);
        }

        .error-box {
            background: #fee2e2;
            border-left: 4px solid #ef4444;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .footer-links {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--text-muted);
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }

        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-links a:hover { color: var(--primary); }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
                margin: 20px;
                border-radius: 20px;
            }
            .left-panel { padding: 40px 20px; }
            .right-panel { padding: 40px 30px; }
            .panel-title { font-size: 32px; }
        }
    </style>
</head>

<body>
    <div class="bg-pattern"></div>
    <div class="neon-glow" style="top: -100px; left: -100px;"></div>
    <div class="neon-glow" style="bottom: -100px; right: -100px; background: rgba(99, 102, 241, 0.2);"></div>

    <div class="main-container">
        <!-- Left Panel (Switch to Register) -->
        <div class="left-panel">
            <p class="panel-subtitle" style="margin-bottom: 10px; opacity: 0.7;">You can easily</p>
            <h2 class="panel-title" style="font-size: 32px;">{{ $companyName }}</h2>
        </div>

        <!-- Right Panel (Login Form) -->
        <div class="right-panel">
            <div class="form-header">
                <h1 class="form-title">Sign In</h1>
                <p class="form-subtitle">Welcome back! Please login to your account.</p>
            </div>

            @if ($errors->any())
                <div class="error-box">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="id_user" class="form-label">Username / Email</label>
                    <div class="input-wrapper">
                        <input type="text" class="form-input" id="id_user" name="id_user" placeholder="Enter your username" required autofocus value="{{ old('id_user') }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-wrapper">
                        <input type="password" class="form-input" id="password" name="password" placeholder="••••••••" required>
                        <span class="password-toggle" onclick="togglePassword()">
                            <i class="ti ti-eye-off" id="eye-icon"></i>
                        </span>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="footer-links">
                <a href="#">Select Language</a>
                <a href="#">Help</a>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms</a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.replace('ti-eye-off', 'ti-eye');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.replace('ti-eye', 'ti-eye-off');
            }
        }
    </script>
</body>

</html>

