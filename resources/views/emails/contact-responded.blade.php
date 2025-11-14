<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>We've Responded to Your Inquiry</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0; font-size: 24px;">We've Responded to Your Inquiry</h1>
        <p style="color: rgba(255,255,255,0.8); margin: 10px 0 0 0;">Thank you for contacting us</p>
    </div>

    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e9ecef;">
        <p>Hello {{ $contactMessage->name }},</p>

        <p>Thank you for reaching out to us through our contact form. We wanted to let you know that we have received and reviewed your inquiry:</p>

        <div style="background: white; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0;">
            <p style="margin: 0; font-weight: bold; color: #2c5282;">Subject: {{ $contactMessage->subject }}</p>
            <p style="margin: 10px 0 0 0; color: #4a5568;">{{ Str::limit($contactMessage->message, 200) }}</p>
        </div>

        <p>Our team has reviewed your message and will be following up with you shortly. If you have any additional questions or concerns, please don't hesitate to reply to this email.</p>

        <div style="background: #e7f3ff; border-left: 4px solid #667eea; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; color: #2c5282; font-size: 14px;">
                <strong>Note:</strong> This is an automated confirmation that your inquiry has been received and is being processed.
            </p>
        </div>

        <p>Thank you for your patience and for choosing to contact us!</p>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e9ecef; font-size: 12px; color: #6c757d;">
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
    </div>
</body>
</html>
