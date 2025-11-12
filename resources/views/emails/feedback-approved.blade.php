<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Feedback Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white; }
        .content { background: #f9fafb; padding: 30px; border-radius: 0 0 8px 8px; }
        .button { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 5px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Feedback Approved</h1>
            <p>Thank you for contributing to {{ config('app.name') }}</p>
        </div>
        
        <div class="content">
            <h2>Hello {{ $feedback->name }},</h2>
            
            <p>We're pleased to inform you that your feedback for <strong>{{ $person->display_name }}</strong> has been reviewed and approved by our team.</p>
            
            <div style="background: white; padding: 20px; border-radius: 8px; border-left: 4px solid #667eea; margin: 20px 0;">
                <h3 style="margin-top: 0;">Your Feedback:</h3>
                <p><strong>Type:</strong> {{ ucfirst($feedback->type) }}</p>
                <p><strong>Message:</strong> {{ \Illuminate\Support\Str::limit(strip_tags($feedback->message), 100) }}</p>
                
                @if($feedback->suggested_changes)
                    <p><strong>Suggested Changes:</strong></p>
                    <ul>
                        @foreach($feedback->suggested_changes as $field => $value)
                            <li><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}:</strong> {{ $value }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
            
            <p>Our content team will implement the approved changes shortly. We appreciate your help in keeping our information accurate and up-to-date.</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ route('people.people.show', $person->slug) }}" class="button" style="color: white;">
                    View {{ $person->display_name }}'s Profile
                </a>
            </p>
            
            <p>Thank you for being a valuable member of our community!</p>
            
            <p>Best regards,<br>The {{ config('app.name') }} Team</p>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p>This is an automated message, please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>