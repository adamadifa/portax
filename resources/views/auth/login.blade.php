<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Login | PORTAL CV. Makmur Permata</title>
    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('/assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('/assets/vendor/fonts/tabler-icons.css') }}" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            width: 100%;
            display: flex;
            min-height: 600px;
        }

        .left-panel {
            flex: 1;
            background: #03204f;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            min-width: 300px;
        }

        .asterisk-icon {
            width: 32px;
            height: 32px;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .promo-text {
            color: white;
            margin-top: auto;
            margin-bottom: 40px;
        }

        .promo-text-small {
            font-size: 16px;
            font-weight: 400;
            margin-bottom: 12px;
            opacity: 0.9;
        }

        .promo-text-large {
            font-size: 28px;
            font-weight: 700;
            line-height: 1.3;
        }

        .right-panel {
            flex: 2;
            padding: 50px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-header {
            margin-bottom: 40px;
        }

        .form-header-icon {
            width: 32px;
            height: 32px;
            color: #03204f;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-title {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 12px;
        }

        .form-description {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.2s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #03204f;
            box-shadow: 0 0 0 3px rgba(3, 32, 79, 0.1);
        }

        .password-wrapper {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            font-size: 18px;
            padding: 4px;
        }

        .password-toggle:hover {
            color: #03204f;
        }

        .btn-primary {
            width: 100%;
            padding: 16px;
            background: #ffc800;
            color: #1a1a1a;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 200, 0, 0.4);
            background: #ffd700;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 32px 0;
            color: #9ca3af;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .divider-text {
            padding: 0 16px;
        }

        .social-buttons {
            display: flex;
            gap: 12px;
            margin-bottom: 32px;
        }

        .social-btn {
            flex: 1;
            padding: 14px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #1a1a1a;
        }

        .social-btn:hover {
            border-color: #03204f;
            background: #f9fafb;
        }

        .signup-link {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
        }

        .signup-link a {
            color: #03204f;
            text-decoration: none;
            font-weight: 500;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fee2e2;
            color: #dc2626;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column;
                min-height: auto;
            }

            .left-panel {
                min-height: 200px;
                padding: 30px;
            }

            .right-panel {
                padding: 40px 30px;
            }

            .form-title {
                font-size: 28px;
            }

            .promo-text-large {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Left Panel -->
        <div class="left-panel">
            <div class="asterisk-icon">*</div>
            <div class="promo-text">
                <div class="promo-text-small">You can easily</div>
                <div class="promo-text-large">Get access your personal hub for clarity and productivity</div>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="right-panel">
            <div class="form-header">
                <div class="form-header-icon">*</div>
                <h1 class="form-title">Login</h1>
                <p class="form-description">Access your tasks, notes, and projects anytime, anywhere - and keep everything flowing in one place.</p>
            </div>

            @if ($errors->has('id_user') || $errors->any())
                <div class="error-message">
                    {{ $errors->first('id_user') ?? $errors->first() }}
                </div>
            @endif

            <form id="formAuthentication" action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="id_user" class="form-label">Username / Email</label>
                    <input type="text" class="form-input" id="id_user" name="id_user" placeholder="username@example.com" autofocus
                        value="{{ old('id_user') }}" />
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" class="form-input" name="password" placeholder="••••••••••"
                            autocomplete="current-password" />
                        <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                            <i class="ti ti-eye-off" id="password-icon"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Sign in</button>
            </form>

            <div class="divider">
                <span class="divider-text">or continue with</span>
            </div>


        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('ti-eye-off');
                passwordIcon.classList.add('ti-eye');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('ti-eye');
                passwordIcon.classList.add('ti-eye-off');
            }
        }
    </script>
</body>

</html>
