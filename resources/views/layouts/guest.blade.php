<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>XENA - {{ $title ?? 'Authentication' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'SF Pro Rounded', 'Inter', sans-serif;
                background: #D3D3D3;
                margin: 0;
                padding: 0;
                position: relative;
                width: 100%;
                min-height: 100vh;
            }
            
            .login-card {
                width: 556px;
                height: 436px;
                background: #FFFFFF;
                box-shadow: 57px 38px 28px rgba(0, 0, 0, 0.01), 32px 21px 23px rgba(0, 0, 0, 0.05), 14px 9px 17px rgba(0, 0, 0, 0.09), 4px 2px 9px rgba(0, 0, 0, 0.1);
                border-radius: 14px;
                position: relative;
            }
            
            .sign-in-title {
                position: absolute;
                width: 98px;
                height: 38px;
                left: 50%;
                transform: translateX(-50%);
                top: 62px;
                font-family: 'SF Pro Rounded', sans-serif;
                font-style: normal;
                font-weight: 700;
                font-size: 32px;
                line-height: 38px;
                background: linear-gradient(90deg, #0C1D25 21.51%, #1F4A5E 57.46%, #2D6D8B 85.47%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
            
            .input-wrapper {
                position: absolute;
                width: 344px;
                left: 50%;
                transform: translateX(-50%);
            }
            
            .username-field {
                top: 149px;
            }
            
            .password-field {
                top: 224px;
            }
            
            .input-box {
                box-sizing: border-box;
                width: 344px;
                height: 45px;
                border: 1px solid #212E62;
                border-radius: 9px;
                padding: 12px 16px;
                font-family: 'SF Pro Rounded', sans-serif;
                font-size: 16px;
                outline: none;
            }
            
            .input-label {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 0px 6px;
                gap: 10px;
                position: absolute;
                height: 19px;
                left: 10px;
                top: -10px;
                background: #FFFFFF;
                font-family: 'SF Pro Rounded', sans-serif;
                font-style: normal;
                font-weight: 400;
                font-size: 16px;
                line-height: 19px;
                color: #000000;
            }
            
            .btn-login {
                display: flex;
                flex-direction: row;
                justify-content: center;
                align-items: center;
                padding: 7px 29px;
                gap: 10px;
                position: absolute;
                width: 109px;
                height: 38px;
                left: 50%;
                transform: translateX(-50%);
                bottom: 69px;
                background: #212E62;
                border-radius: 9px;
                border: none;
                cursor: pointer;
                font-family: 'SF Pro Rounded', sans-serif;
                font-style: normal;
                font-weight: 700;
                font-size: 20px;
                line-height: 24px;
                color: #FFFFFF;
            }
            
            .btn-login:hover {
                background: #1a2450;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <div style="position: absolute; width: 100%; height: 80px; left: 0px; top: 0px; background: #FFFFFF; display: flex; align-items: center;">
            <div style="margin-left: 33px;">
                <img src="{{ asset('images/logoXENA.png') }}" alt="XENA" style="height: 26px;">
            </div>
        </div>

        <!-- Main Content -->
        <div style="position: absolute; width: 100%; height: calc(100vh - 100px); top: 100px; display: flex; justify-content: center; align-items: center;">
            <div class="login-card" style="position: absolute; width: 556px; height: 436px; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                <!-- Sign in Title -->
                <h2 class="sign-in-title">Sign in</h2>
                
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email / Username Field -->
                    <div class="input-wrapper username-field">
                        <div style="position: relative;">
                            <span class="input-label">Email / Username</span>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username"
                                class="input-box"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password Field -->
                    <div class="input-wrapper password-field">
                        <div style="position: relative;">
                            <span class="input-label">Password</span>
                            <input 
                                id="password" 
                                type="password" 
                                name="password" 
                                required 
                                autocomplete="current-password"
                                class="input-box"
                            />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </body>
</html>