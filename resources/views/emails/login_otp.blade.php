<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login OTP</title>
</head>
<body style="margin: 0; padding: 0; background-color: #F8FAFC; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1E293B;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #F8FAFC; padding: 40px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="540px" cellspacing="0" cellpadding="0" style="max-width: 540px; background-color: #FFFFFF; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #E2E8F0; overflow: hidden;">
                    {{-- Header Banner --}}
                    <tr>
                        <td style="background: #2563EB; padding: 28px 30px; text-align: center;">
                            <h1 style="color: #FFFFFF; margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.3px;">
                                {{ $siteName }}
                            </h1>
                            <p style="color: #DBEAFE; margin: 6px 0 0; font-size: 13px; font-weight: 500;">
                                Secure Account Verification
                            </p>
                        </td>
                    </tr>

                    {{-- Body Content --}}
                    <tr>
                        <td style="padding: 32px 30px 24px;">
                            <h2 style="font-size: 18px; font-weight: 700; color: #0F172A; margin: 0 0 12px;">
                                Your One-Time Password (OTP)
                            </h2>
                            <p style="font-size: 14.5px; color: #475569; line-height: 1.6; margin: 0 0 24px;">
                                Use the 6-digit verification code below to securely log in to your 
                                <strong>{{ ucfirst($userType) }}</strong> account on {{ $siteName }}.
                            </p>

                            {{-- OTP Display Box --}}
                            <div style="background: #EFF6FF; border: 2px dashed #93C5FD; border-radius: 12px; padding: 20px; text-align: center; margin: 0 0 24px;">
                                <div style="font-size: 36px; font-weight: 800; letter-spacing: 8px; color: #1D4ED8; font-family: monospace;">
                                    {{ $otpCode }}
                                </div>
                                <div style="font-size: 12px; color: #64748B; font-weight: 600; margin-top: 8px;">
                                    Valid for {{ $expiresMinutes }} minutes • Do not share with anyone
                                </div>
                            </div>

                            {{-- Security Notice --}}
                            <div style="background: #FEF3C7; border-left: 4px solid #F59E0B; padding: 12px 16px; border-radius: 6px; margin: 0 0 24px;">
                                <p style="font-size: 12.5px; color: #92400E; margin: 0; line-height: 1.5;">
                                    <strong>Security Tip:</strong> If you did not request this login code, someone may have mistakenly entered your email. No action is required and your account remains safe.
                                </p>
                            </div>

                            <p style="font-size: 13px; color: #94A3B8; margin: 0;">
                                Best regards,<br>
                                <strong style="color: #475569;">{{ $siteName }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color: #F8FAFC; border-top: 1px solid #F1F5F9; padding: 16px 30px; text-align: center; font-size: 11.5px; color: #94A3B8;">
                            This is an automated security message from {{ $siteName }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
