<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Your Email - {{ config('app.name') }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 40px;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            text-align: center;
            color: #2563eb;
            margin: 30px 0;
            letter-spacing: 8px;
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 2px dashed #e2e8f0;
        }
        .info-box {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .button {
            display: inline-block;
            background: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
        }
        .person-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            border-left: 4px solid #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px;">Verify Your Email</h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">Complete your feedback submission</p>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p>You're suggesting an edit for <strong>{{ $person->name }}</strong>'s profile. To complete your submission, please use the verification code below:</p>

            <div class="otp-code">{{ $otp }}</div>

            <div class="info-box">
                <strong>⚠️ This code will expire at:</strong><br>
                {{ $expires_at->format('F j, Y g:i A T') }}
            </div>

            <div class="person-card">
                <strong>Profile you're editing:</strong><br>
                {{ $person->name }}
                @if($person->professions && count($person->professions))
                    <br><small>Profession: {{ implode(', ', $person->professions) }}</small>
                @endif
            </div>

            <p>If you didn't request this verification, please ignore this email. Your email address will not be used for any other purpose.</p>

            <p>Thank you for helping us improve our content!</p>
        </div>

        <div class="footer">
            <p>Best regards,<br><strong>{{ config('app.name') }}</strong></p>
            <p style="font-size: 12px; color: #9ca3af;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>
