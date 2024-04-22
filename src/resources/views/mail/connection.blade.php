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
                                    <h1 style="color: #fff !important; font-size: 30px !important; text-align: center !important;">You have a new connection!</h1>
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
                                                <h1 style="color: #000 !important; font-size: 25px !important; font-weight: normal !important; text-align: center !important;"><strong>{{ $name }}</strong> has accepted your invite!</h1>
                                                <hr style="margin-left: 80px !important; margin-right: 80px !important">
                                            </div>

                                            <x-mail::button :url="$url" color="green">
                                                See profile
                                            </x-mail::button>

                                            <i>
                                                If you're having trouble clicking the "See profile" button, kindly check your networks tab on our website.
                                            </i>
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
