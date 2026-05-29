<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - XENA</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            padding: 20px;
        }
        .email-container {
            max-width: 500px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #0C1D25 0%, #1F4A5E 56%, #2D6D8B 100%);
            padding: 30px;
            text-align: center;
        }
        .logo {
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h1 {
            color: #1a202c;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            color: #4b5563;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .btn-reset {
            display: inline-block;
            background: #1e3a8a;
            color: #ffffff;
            padding: 14px 32px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
        }
        .btn-reset:hover {
            background: #1e40af;
        }
        .link-text {
            word-break: break-all;
            color: #1e40af;
            font-size: 12px;
            background: #f3f4f6;
            padding: 12px;
            border-radius: 6px;
            margin-top: 15px;
        }
        .email-footer {
            background: #f9fafb;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #9ca3af;
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">XENA</div>
        </div>
        <div class="email-body">
            <h1>Reset Password</h1>
            <p>Hello,</p>
            <p>We received a request to reset your password. Click the button below to create a new password:</p>

            <center>
                <a href="{{ $resetUrl }}" class="btn-reset">Reset Password</a>
            </center>

            <p>If you didn't request a password reset, please ignore this email. This link will expire in 60 minutes.</p>

            <p>Or copy and paste this link:</p>
            <div class="link-text">{{ $resetUrl }}</div>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} XENA. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
