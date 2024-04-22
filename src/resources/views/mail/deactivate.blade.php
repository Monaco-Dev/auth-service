<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="color-scheme" content="light">
        <meta name="supported-color-schemes" content="light">

        <style>
            @media only screen and (max-width: 600px) {
                .inner-body {
                    width: 100% !important;
                }

                .footer {
                    width: 100% !important;
                }
            }

            @media only screen and (max-width: 500px) {
                .button {
                    width: 100% !important;
                }
            }

            body {
                color: black !important;
            }
        </style>
    </head>

    <body>
        <table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center">
                    <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                        <!-- Email Body -->
                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="text-align: center; border: hidden !important; padding-top: 10px !important">
                                <div class="inner-body" style="padding-top: 10px !important; padding-bottom: 10px !important">
                                    <a href="{{ config('services.web_url') }}" style="display: inline-block;">
                                        <img src="https://storage.googleapis.com/realmateph/brand.png" width="150" alt="Realmate">
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 8px 0"></td>
                        </tr>

                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="text-align: center; border: hidden !important">
                                <div class="inner-body" style="text-align: center !important; background-color: #32CD32">
                                    <h1 style="color: #fff !important; font-size: 30px !important; text-align: center !important;">Account deactivated</h1>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="padding: 25px !important">
                                            <div style="text-align: center !important">
                                                <h1 style="color: #000 !important; font-size: 25px !important; font-weight: normal !important; text-align: center !important;">Hello <strong>{{ $name }}</strong>,</h1>
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <p>
                                                Your account has been deactivated. We're really sorry to see you go. But we really appreciate you for giving us a try
                                            </p>

                                            <p>
                                                <strong>Made a mistake? Having second thoughts?</strong>
                                                <br>
                                                If this deactivation is an error or you have any other questions about your account, you can contact us at: <a href="mailto:info@realmate.ph">info@realmate.ph</a> or visit our facebook page <a href="https://www.facebook.com/realmateph">https://www.facebook.com/realmateph</a>
                                            </p>

                                            <p>
                                                <strong>Help us improve</strong>
                                                <br>
                                                We'd love to know why you deactivated. We're always looking for ways to get better and provide you the best service. If you have a feedback you think would be useful, you can send us a feedback at: <a href="mailto:info@realmate.ph">info@realmate.ph</a> or visit our facebook page <a href="https://www.facebook.com/realmateph">https://www.facebook.com/realmateph</a>
                                            </p>

                                            <p>
                                                Thank you again for being part of Realmate. We're really hoping you'll be part of our community once again.
                                            </p>

                                            <p>
                                                Sincerely,
                                                <br>
                                                Realmate Team
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <x-mail::footer>
                            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
                        </x-mail::footer>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>
