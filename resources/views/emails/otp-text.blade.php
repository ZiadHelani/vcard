Hello {{ $user->name ?? 'User' }}!

We received a request for {{ $purpose }} on your Ultra Tech account.

Your Verification Code: {{ $otpCode }}

This code expires in 10 minutes.

Instructions:
- Enter this code in the verification field on your device
- Make sure to enter all digits exactly as shown
- The code is case-sensitive and expires in 10 minutes
- If the code expires, you can request a new one

SECURITY NOTICE:
Never share this code with anyone! Our team will never ask for your verification code.
If you didn't request this code, please ignore this email or contact our support team immediately.

If you have any questions or need assistance, feel free to contact our support team.

Best regards,
The Ultra Tech Team

---
This email was sent to {{ $user->email }}
© {{ date('Y') }} Ultra Tech Platform. All rights reserved.
This is an automated message, please do not reply to this email.
