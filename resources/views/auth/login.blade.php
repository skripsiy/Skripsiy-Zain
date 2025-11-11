<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XENA - Sign in</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #C8C8C8;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .header {
            background: #FFFFFF;
            padding: 20px 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(90deg, #0C1D25 0%, #1F4A5E 56%, #2D6D8B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 0.5px;
        }
        .container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }
        .login-box {
            background: #FFFFFF;
            border-radius: 12px;
            padding: 50px 60px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            width: 100%;
            max-width: 420px;
        }
        .login-title {
            font-size: 28px;
            font-weight: 600;
            background: linear-gradient(90deg, #0C1D25 0%, #1F4A5E 56%, #2D6D8B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-align: center;
            margin-bottom: 40px;
        }
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        .form-label {
            position: absolute;
            top: -8px;
            left: 12px;
            background: #FFFFFF;
            padding: 0 6px;
            font-size: 13px;
            color: #666;
            font-weight: 500;
        }
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 1.5px solid #D0D0D0;
            border-radius: 6px;
            font-size: 15px;
            transition: all 0.2s;
            background: #FFFFFF;
        }
        .form-input:focus {
            outline: none;
            border-color: #2C3E7C;
        }
        .login-button {
            width: 100%;
            padding: 14px;
            background: #2C3E7C;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        .login-button:hover {
            background: #1f2d5a;
        }
        .error-message {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">XENA</div>
    </div>
    
    <div class="container">
        <div class="login-box">
            <h1 class="login-title">Sign in</h1>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Email / Username</label>
                    <input 
                        type="email" 
                        name="email" 
                        class="form-input"
                        value="{{ old('email') }}"
                        required 
                        autofocus>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        class="form-input"
                        required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                
                <button type="submit" class="login-button">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
