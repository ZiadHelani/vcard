<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>

    <style>
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

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

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
        }

        .header-subtitle {
            font-size: 16px;
            opacity: 0.9;
        }

        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 20px;
        }

        .message {
            font-size: 15px;
            color: #4a5568;
            margin-bottom: 25px;
        }

        .contact-section {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            border-radius: 12px;
            padding: 25px;
            border: 2px dashed #cbd5e0;
        }

        .field {
            margin-bottom: 15px;
        }

        .field-label {
            font-size: 13px;
            color: #718096;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .field-value {
            font-size: 15px;
            color: #2d3748;
            font-weight: 500;
            background: #ffffff;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }

        .message-box {
            white-space: pre-line;
        }

        .footer-note {
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
        }

        .email-footer {
            background-color: #f7fafc;
            padding: 25px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }

        .company-info {
            color: #a0aec0;
            font-size: 12px;
        }

        @media only screen and (max-width: 600px) {
            .email-content,
            .email-header,
            .email-footer {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="email-container">

    <!-- Header -->
    <div class="email-header">
        <div class="logo">Ultra Tech</div>
        <div class="header-subtitle">New Contact Message</div>
    </div>

    <!-- Content -->
    <div class="email-content">

        <div class="greeting">
            📩 You received a new message
        </div>

        <div class="message">
            A visitor has submitted a contact form from your digital business card.
            Here are the details:
        </div>

        <!-- Contact Data -->
        <div class="contact-section">

            <div class="field">
                <div class="field-label">Name</div>
                <div class="field-value">{{ $data['name'] }}</div>
            </div>

            <div class="field">
                <div class="field-label">Phone</div>
                <div class="field-value">{{ $data['phone'] }}</div>
            </div>

            <div class="field">
                <div class="field-label">Subject</div>
                <div class="field-value">{{ $data['subject'] }}</div>
            </div>

            <div class="field">
                <div class="field-label">Message</div>
                <div class="field-value message-box">{{ $data['message'] }}</div>
            </div>

        </div>

        <div class="footer-note">
            You can reply directly to this user using the provided phone number.
        </div>

    </div>

    <!-- Footer -->
    <div class="email-footer">
        <div class="company-info">
            © {{ date('Y') }} Ultra Tech Platform. All rights reserved.<br>
            This is an automated notification from your digital business card.
        </div>
    </div>

</div>

</body>
</html>
