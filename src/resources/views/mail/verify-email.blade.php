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
                                    <h1 style="color: #fff !important; font-size: 30px !important; text-align: center !important;">Email Verification</h1>
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
                                                Hi <strong>{{ $name }}</strong>!
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <br>

                                            You’re almost set in verifying your account. Click the button the below to verify your email address:

                                            <x-mail::button :url="$url" color="green">
                                                Verify email address
                                            </x-mail::button>

                                            <strong>Please note:</strong>

                                            <ul>
                                                <li>
                                                    If you’ve already submitted your real estate license for account verification, please <strong>allow up to 24 hours</strong> for the verification process.
                                                </li>

                                                <li>
                                                    The verification process will begin only after you have submitted a selfie with your <strong>PRC real estate brokers license</strong> or <strong>DHUSD license for the salespersons</strong>.
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 8px 0"></td>
                        </tr>

                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="padding: 25px !important">
                                            <div style="text-align: center !important">
                                                <strong>Why do I need to verify my account?</strong>
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <br>

                                            <p>
                                                Account verification is a legal requirement that is mandated by our regulatory authorities. It also excludes non-real estate service practitioners from our website. As the owner of a licensed real estate account, you will enjoy the complete Realmate experience.
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 8px 0"></td>
                        </tr>

                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="padding: 25px !important">
                                            <div style="text-align: center !important">
                                                <strong>What do you need to submit?</strong>
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <br>

                                            <p>
                                                <strong>Your selfie with real estate license</strong>
                                            </p>

                                            <p>
                                                We only accept valid <strong>PRC real estate brokers license</strong> or <strong>DHUSD license for the salespersons</strong>. If you still don't have either of the two, you can submit an <strong>official receipt</strong> of your renewal from <strong>PRC</strong> or <strong>DHSUD</strong>.
                                            </p>

                                            <p>
                                                <i>For more concerns regarding the verification, you may message us through our facebook page: <a href="https://facebook.com/realmateph" target="_blank">https://facebook.com/realmateph</a></i>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td style="padding: 8px 0"></td>
                        </tr>

                        <tr>
                            <td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
                                <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                                    <!-- Body content -->
                                    <tr>
                                        <td class="content-cell" style="padding: 25px !important">
                                            <div style="text-align: center !important">
                                                <strong>What do I need to do next?</strong>
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <br>

                                            <p>
                                                Kindly wait for approximately 24 hours for your account verification. We will notify you when verification is complete.
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
