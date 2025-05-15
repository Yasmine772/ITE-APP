<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to Our App</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f2f4f6;">

<table role="presentation" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" style="padding: 40px 0;">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden;">

                <!-- Header -->
                <tr>
                    <td style="background-color: #4f46e5; padding: 30px; text-align: center;">
                        <h1 style="color: #ffffff; margin: 0; font-size: 24px;">Welcome to Our Platform!</h1>
                    </td>
                </tr>

                <!-- Body -->
                <tr>
                    <td style="padding: 40px 30px 30px;">
                        <h2 style="margin-top: 0; color: #333333;">Hi {{ $user->name }},</h2>
                        <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                            We're excited to have you on board. Thank you for registering on our application — we’re confident you’ll love what we’ve built just for you.
                        </p>
                        <p style="color: #555555; font-size: 16px; line-height: 1.6;">
                            If you ever have any questions or need help, feel free to reach out to our support team anytime.
                        </p>

                        <p style="color: #999999; font-size: 14px; text-align: center;">
                            Thanks again,<br>The Team
                        </p>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background-color: #f9fafb; padding: 20px; text-align: center;">
                        <p style="color: #aaaaaa; font-size: 12px;">
                            &copy; {{ date('Y') }} Your Company. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>

</body>
</html>
