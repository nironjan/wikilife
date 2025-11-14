<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Email - Contact Form</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">Verify Your Email</h1>
        <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0 0;">Contact Form Verification</p>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e9ecef;">
        <p>Hello,</p>

        <p>You're receiving this email because you're trying to contact us through our website. To ensure the security of our communication and prevent spam, please verify your email address using the code below:</p>

        <div style="text-align: center; margin: 30px 0;">
            <div style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #667eea; background: #f1f3f4; padding: 20px; border-radius: 8px; display: inline-block;">
                {{ $otp }}
            </div>
        </div>

        <p>This verification code will expire in <strong>30 minutes</strong>.</p>

        <div style="background: #e7f3ff; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; color: #2c5282; font-size: 14px;">
                <strong>Note:</strong> If you didn't request this verification code, please ignore this email. Your email address will not be used for any communication.
            </p>
        </div>

        <p>Thank you for reaching out to us!</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; font-size: 12px; color: #6c757d;">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
