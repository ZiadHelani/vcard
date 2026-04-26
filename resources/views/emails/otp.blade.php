<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $purpose }} - OTP Code</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333333;
            background-color: #f8fafc;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .logo {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 300;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            color: #4a5568;
            margin-bottom: 30px;
            line-height: 1.7;
        }

        /* OTP Code Section */
        .otp-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
            border: 2px dashed #cbd5e0;
        }

        .otp-label {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
        }

        .otp-code {
            font-size: 36px;
            font-weight: 700;
            color: #2d3748;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
            background: white;
            padding: 20px 30px;
            border-radius: 8px;
            display: inline-block;
            border: 3px solid #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
        }

        .otp-validity {
            font-size: 14px;
            color: #e53e3e;
            margin-top: 15px;
            font-weight: 500;
        }

        /* Instructions */
        .instructions {
            background-color: #ebf8ff;
            border-left: 4px solid #3182ce;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }

        .instructions h3 {
            color: #2c5282;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .instructions ul {
            color: #2d3748;
            padding-left: 20px;
        }

        .instructions li {
            margin-bottom: 8px;
            font-size: 14px;
        }

        /* Security Notice */
        .security-notice {
            background-color: #fff5f5;
            border: 1px solid #fed7d7;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }

        .security-notice h3 {
            color: #c53030;
            font-size: 16px;
            margin-bottom: 10px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .security-icon {
            margin-right: 8px;
            font-size: 18px;
        }

        .security-notice p {
            color: #742a2a;
            font-size: 14px;
            line-height: 1.6;
        }

        /* Footer */
        .email-footer {
            background-color: #f7fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .footer-text {
            color: #718096;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .company-info {
            color: #a0aec0;
            font-size: 12px;
        }

        /* Responsive Design */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 10px;
                border-radius: 8px;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 25px 20px;
            }

            .otp-section {
                padding: 20px;
                margin: 20px 0;
            }

            .otp-code {
                font-size: 28px;
                letter-spacing: 4px;
                padding: 15px 20px;
            }

            .greeting {
                font-size: 20px;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            .email-container {
                background-color: #1a202c;
            }

            .email-content {
                background-color: #1a202c;
            }

            .greeting {
                color: #f7fafc;
            }

            .message {
                color: #cbd5e0;
            }

            .otp-section {
                background: linear-gradient(135deg, #2d3748 0%, #4a5568 100%);
                border-color: #4a5568;
            }

            .otp-code {
                background: #2d3748;
                color: #f7fafc;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">Ultra Tech</div>
            <div class="header-subtitle">Digital Business Card Platform</div>
        </div>

        <!-- Content -->
        <div class="email-content">
            <div class="greeting">
                Hello {{ $user->name ?? 'User' }}! 👋
            </div>

            <div class="message">
                We received a request for <strong>{{ $purpose }}</strong> on your Ultra Tech account.
                To complete this process, please use the verification code below:
            </div>

            <!-- OTP Code Section -->
            <div class="otp-section">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code">{{ $otpCode }}</div>
                <div class="otp-validity">⏰ This code expires in 10 minutes</div>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>📋 How to use this code:</h3>
                <ul>
                    <li>Enter this code in the verification field on your device</li>
                    <li>Make sure to enter all digits exactly as shown</li>
                    <li>The code is case-sensitive and expires in 10 minutes</li>
                    <li>If the code expires, you can request a new one</li>
                </ul>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <h3>
                    <span class="security-icon">🔒</span>
                    Security Notice
                </h3>
                <p>
                    <strong>Never share this code with anyone!</strong> Our team will never ask for your verification code.
                    If you didn't request this code, please ignore this email or contact our support team immediately.
                </p>
            </div>

            <div class="message">
                If you have any questions or need assistance, feel free to contact our support team.
                <br><br>
                Best regards,<br>
                <strong>The Ultra Tech Team</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                This email was sent to <strong>{{ $user->email }}</strong>
            </div>
            <div class="company-info">
                © {{ date('Y') }} Ultra Tech Platform. All rights reserved.<br>
                This is an automated message, please do not reply to this email.
            </div>
        </div>
    </div>
</body>
</html>
