<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Forgot Password | Portax</title>
    
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

        .main-container {
            width: 100%;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            display: flex;
            overflow: hidden;
            min-height: 500px;
            position: relative;
            z-index: 10;
        }

        /* Left Panel */
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
        }

        .panel-title { font-size: 32px; font-weight: 700; margin-bottom: 20px; }
        .panel-subtitle { font-size: 15px; opacity: 0.9; margin-bottom: 40px; max-width: 250px; }

        .btn-outline {
            padding: 12px 30px;
            border: 2px solid white;
            border-radius: 30px;
            background: transparent;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-outline:hover { background: white; color: var(--primary); }

        /* Right Panel */
        .right-panel {
            flex: 1.5;
            padding: 60px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header { margin-bottom: 30px; }
        .form-title { font-size: 28px; font-weight: 700; color: var(--text-main); margin-bottom: 10px; }
        .form-description { font-size: 14px; color: var(--text-muted); line-height: 1.6; }

        .form-group { margin-bottom: 25px; }
        .form-label { display: block; font-size: 14px; font-weight: 500; color: var(--text-main); margin-bottom: 8px; }
        .form-input { width: 100%; padding: 14px 16px; background: #f3f4f6; border: 2px solid transparent; border-radius: 12px; font-size: 14px; }
        .form-input:focus { outline: none; border-color: var(--primary); background: white; }

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
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
        }

        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.1); }

        .status-message { background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; }

        @media (max-width: 768px) {
            .main-container { flex-direction: column; border-radius: 20px; min-height: auto; }
            .right-panel { padding: 40px 30px; }
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="left-panel">
            <h2 class="panel-title">Forgot Password?</h2>
            <p class="panel-subtitle">Don't worry, it happens. Just enter your email and we'll send a link.</p>
            <a href="{{ route('login') }}" class="btn-outline">Back to Sign In</a>
        </div>

        <div class="right-panel">
            <div class="form-header">
                <h1 class="form-title">Reset Password</h1>
                <p class="form-description">We will send a password reset link to your email address.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="status-message" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input class="form-input" id="email" type="email" name="email" placeholder="yourname@email.com" :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
                </div>

                <button type="submit" class="btn-submit">Email Password Reset Link</button>
            </form>
        </div>
    </div>
</body>
</html>
