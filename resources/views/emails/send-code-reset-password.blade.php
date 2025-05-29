<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 30px;
        }

        .container {
            background-color: #ffffff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }

        h1 {
            color: #2c3e50;
        }

        .code-box {
            background-color: #e9ecef;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
            letter-spacing: 2px;
        }

        p.note {
            font-size: 14px;
            color: #6c757d;
        }

        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #999;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>🔐 Password Reset Request</h1>
    <p>We have received your request to reset your account password.</p>
    <p>You can use the following code to recover your account:</p>

    <div class="code-box">{{ $code }}</div>

    <p class="note">The code is valid for one hour from the time this message was sent.</p>

    <div class="footer">
        If you did not request this, you can safely ignore this email.<br>
        — {{ config('app.name') }} Team
    </div>
</div>
</body>
</html>

