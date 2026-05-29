<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - XENA</title>
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

        /* Background image with black and white effect */
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

        /* Dark overlay for better contrast */
        body::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.4) 100%);
            z-index: 0;
            pointer-events: none;
        }

        /* Header */
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

        /* Main container */
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1.5rem;
            position: relative;
            z-index: 1;
        }

        /* Login card */
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

        /* Title */
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

        /* Form */
        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }

        /* Fieldset style for label */
        .input-fieldset {
            border: 2px solid #1a202c;
            border-radius: 0.75rem;
            padding: 0;
            margin: 0;
            position: relative;
        }

        .input-legend {
            font-size: 0.875rem;
            color: #1a202c;
            font-weight: 500;
            padding: 0 0.5rem;
            margin-left: 0.75rem;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            border: none;
            outline: none;
            background: transparent;
            color: #1a202c;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
        }

        .form-input::placeholder {
            color: transparent;
        }

        .input-fieldset:focus-within {
            border-color: #1e40af;
        }

        /* Error messages */
        .error-message {
            color: #e53e3e;
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
            animation: shake 0.3s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Button */
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

        /* Status messages */
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

        /* Responsive */
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

        /* Loading state */
        .btn-login.loading {
            pointer-events: none;
            opacity: 0.7;
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <a href="{{ route('login') }}" class="logo">XENA</a>
    </div>

    <!-- Main Container -->
    <div class="container">
        <div class="login-card">
            <div class="title">
                <h1>Forgot Password</h1>
            </div>

            <div class="description">
                {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="status-message">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" id="resetForm">
                @csrf

                <!-- Email Address -->
                <div class="form-group">
                    <fieldset class="input-fieldset">
                        <legend class="input-legend">Email</legend>
                        <input 
                            id="email" 
                            class="form-input" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus 
                        />
                    </fieldset>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Buttons -->
                <div class="btn-container">
                    <button type="submit" class="btn-login" id="resetBtn">
                        {{ __('Email Password Reset Link') }}
                    </button>
                    <a href="{{ route('login') }}" class="btn-back">Back to Login</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Add loading state on form submit
        document.getElementById('resetForm').addEventListener('submit', function() {
            const btn = document.getElementById('resetBtn');
            btn.classList.add('loading');
        });

        // Add smooth focus transitions
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add ripple effect on button click
        document.querySelector('.btn-login').addEventListener('click', function(e) {
            if(this.classList.contains('loading')) return;
            
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = x + 'px';
            ripple.style.top = y + 'px';
            ripple.style.position = 'absolute';
            ripple.style.borderRadius = '50%';
            ripple.style.background = 'rgba(255, 255, 255, 0.5)';
            ripple.style.transform = 'scale(0)';
            ripple.style.animation = 'ripple 0.6s ease-out';
            ripple.style.pointerEvents = 'none';
            
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>