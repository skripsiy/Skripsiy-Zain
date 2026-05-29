<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - XENA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #1a202c;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: url('/images/cityscape-bg.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: grayscale(100%) brightness(0.4) contrast(1.1) blur(2px);
            z-index: 0;
        }

        body::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.4) 100%);
            z-index: 0;
            pointer-events: none;
        }

        .header {
            background: #FFFFFF;
            padding: 20px 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(90deg, #0C1D25 0%, #1F4A5E 56%, #2D6D8B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.5px;
            display: block;
            text-decoration: none;
        }

        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: white;
            border-radius: 1rem;
            padding: 3rem 2.75rem;
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.4),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            width: 100%;
            max-width: 460px;
            position: relative;
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
            transform-origin: center;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .title {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .title h1 {
            font-size: 1.75rem;
            font-weight: 600;
            margin: 0;
            line-height: 1.2;
            color: #1a202c;
        }

        .description {
            font-size: 0.875rem;
            color: #4b5563;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-login {
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: white;
            background: #1e3a8a;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
            width: 100%;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(30, 58, 138, 0.4);
            background: #1e40af;
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(30, 58, 138, 0.3);
        }

        .btn-back {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        .btn-back:hover {
            color: #1e40af;
        }

        .status-message {
            padding: 0.875rem 1.25rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 0.9375rem;
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
            animation: slideDown 0.4s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .logout-link {
            text-align: center;
            font-size: 0.875rem;
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
            margin-top: 1rem;
            display: block;
        }

        .logout-link:hover {
            color: #e53e3e;
        }

        @media (max-width: 640px) {
            .header {
                padding: 1.25rem 1.5rem;
            }

            .login-card {
                padding: 2.5rem 2rem;
                border-radius: 1.25rem;
            }

            .container {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <a href="{{ route('login') }}" class="logo">XENA</a>
    </div>

    <div class="container">
        <div class="login-card">
            <div class="title">
                <h1>Verify Email</h1>
            </div>

            <div class="description">
                {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="status-message">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="btn-container">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-login">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-link">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
