<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Create Account | Portax</title>
    
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

        .circle-1 { width: 400px; height: 400px; background: var(--primary-light); top: -100px; right: -100px; }
        .circle-2 { width: 300px; height: 300px; background: var(--primary); bottom: -50px; left: -50px; }
        .circle-3 { width: 200px; height: 200px; background: var(--primary-dark); top: 20%; left: 10%; }

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
            min-height: 650px;
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

        /* Removed left-panel decorative circles as requested */

        .panel-title {
            font-size: 36px;
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
        }

        /* Right Panel - Form */
        .right-panel {
            flex: 1.5;
            padding: 60px 80px;
            display: flex;
            flex-direction: column;
            background: white;
        }

        .form-header { margin-bottom: 30px; }
        .form-title { font-size: 32px; font-weight: 700; color: var(--text-main); margin-bottom: 10px; }
        .form-subtitle { font-size: 15px; color: var(--text-muted); }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .full-width { grid-column: span 2; }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            background: #f3f4f6;
            border: 2px solid transparent;
            border-radius: 12px;
            font-size: 14px;
            color: var(--text-main);
            transition: all 0.3s;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
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
            filter: brightness(1.1);
        }

        .helper-text {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 10px;
            text-align: center;
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
        }

        .footer-links {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
            border-top: 1px solid #f3f4f6;
            padding-top: 20px;
        }

        .footer-links a { color: var(--text-muted); text-decoration: none; }
        .footer-links a:hover { color: var(--primary); }

        @media (max-width: 900px) {
            .main-container { flex-direction: column; max-width: 500px; min-height: auto; }
            .right-panel { padding: 40px 30px; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="neon-glow" style="top: -100px; left: -100px;"></div>
    <div class="neon-glow" style="bottom: -100px; right: -100px; background: rgba(99, 102, 241, 0.2);"></div>

    <div class="main-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="left-circle lc-3"></div>
            
            <h2 class="panel-title">Welcome Back 'John'</h2>
            <p class="panel-subtitle">To keep connected with us please login with your personal information</p>
            <a href="{{ route('login') }}" class="btn-outline">Sign In</a>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="form-header">
                <h1 class="form-title">Create Account</h1>
                <p class="form-subtitle">Join us today! It only takes a minute.</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-grid">
                    <!-- Name & Username -->
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input class="form-input" id="name" type="text" name="name" placeholder="John Doe" value="{{ old('name') }}" required autofocus autocomplete="name">
                        @if($errors->has('name')) <p class="error-message">{{ $errors->first('name') }}</p> @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input class="form-input" id="username" type="text" name="username" placeholder="johndoe123" value="{{ old('username') }}" required autocomplete="username">
                        @if($errors->has('username')) <p class="error-message">{{ $errors->first('username') }}</p> @endif
                    </div>

                    <!-- Email -->
                    <div class="form-group full-width">
                        <label class="form-label" for="email">Email Address</label>
                        <input class="form-input" id="email" type="email" name="email" placeholder="yourname@email.com" value="{{ old('email') }}" required autocomplete="email">
                        @if($errors->has('email')) <p class="error-message">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <input class="form-input" id="password" type="password" name="password" placeholder="••••••••" required autocomplete="new-password">
                        @if($errors->has('password')) <p class="error-message">{{ $errors->first('password') }}</p> @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password_confirmation">Confirm</label>
                        <input class="form-input" id="password_confirmation" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password">
                    </div>
                </div>

                <p class="helper-text">Use 8 or more characters with a mix of letters, numbers & symbols</p>
                <button type="submit" class="btn-submit">Create Account</button>
            </form>

            <div class="footer-links">
                <a href="#">Select Language</a>
                <a href="#">Help</a>
                <a href="#">Privacy-Policy</a>
                <a href="#">GDPR</a>
                <a href="#">Terms & Conditions</a>
            </div>
        </div>
    </div>
</body>
</html>
