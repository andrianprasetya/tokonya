<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <style type="text/css">
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
                background-size: 100% 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }

        /* Base */

        body,
        body *:not(html):not(style):not(br):not(tr):not(code) {
            font-family: -apple-system, BlinkMacSystemFont, "Heebo", Roboto, Helvetica,
            Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji",
            "Segoe UI Symbol";
            box-sizing: border-box;
        }

        body {
            background-color: #f8fafc;
            color: #74787e;
            height: 100%;
            hyphens: auto;
            line-height: 1.4;
            margin: 0;
            -moz-hyphens: auto;
            -ms-word-break: break-all;
            width: 100% !important;
            -webkit-hyphens: auto;
            -webkit-text-size-adjust: none;
            word-break: break-all;
            word-break: break-word;
            font-family: "Heebo";
        }

        p,
        ul,
        ol,
        blockquote {
            line-height: 1.4;
            text-align: left;
        }

        a {
            color: #3869d4;
        }

        a img {
            border: none;
        }

        a:hover img {
            border: none !important;
            display: block;
        }

        /* Typography */

        h1 {
            color: #3d4852;
            font-size: 19px;
            font-weight: bold;
            margin-top: 0;
            text-align: center;
        }

        h2 {
            color: #3d4852;
            font-size: 16px;
            font-weight: bold;
            margin-top: 0;
            text-align: center;
        }

        h3 {
            color: #3d4852;
            font-size: 14px;
            font-weight: bold;
            margin-top: 0;
            text-align: center;
        }

        p {
            color: #3d4852;
            font-size: 16px;
            line-height: 1.5em;
            margin-top: 0;
            text-align: center;
        }

        p.sub {
            font-size: 12px;
        }

        img {
            max-width: 100%;
        }

        /* Layout */

        .wrapper {
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            width: 100%;
            border: solid #dddddd 1px;
            border-radius: 11px !important;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .content {
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        /* Header */

        .header {
            padding: 25px 0;
            text-align: center;
            border-top-left-radius: 10px !important;
            border-top-right-radius: 10px !important;
            background-size: 100% 100%;
            background: #74787e no-repeat;
            color: #74787e;
        }

        .header a {
            color: #bbbfc3;
            font-size: 19px;
            font-weight: bold;
            text-decoration: none;
            text-shadow: 0 1px 0 white;
        }

        .header p {
            color: #ffffff;
            font-size: 14px;
            text-align: center;
            margin-bottom: 0;
        }

        /* Body */

        .body {
            background-color: #ffffff;
            border-bottom: 1px solid #edeff2;
            border-top: 1px solid #edeff2;
            margin: 0;
            padding: 0;
            width: 100%;
            text-align: center !important;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .inner-body {
            background-color: #ffffff;
            margin: 0 auto;
            padding: 0;
            width: 100%;
            text-align: center !important;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        /* Subcopy */

        .subcopy {
            margin-top: 25px;
            padding-top: 25px;
        }

        .subcopy p {
            font-size: 12px;
        }

        /* Footer */

        .footer {
            margin: 0 auto;
            padding: 0;
            text-align: center;
            width: 100%;
            height: 50px;
            background-size: 100% 100%;
            background: #74787e no-repeat;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .footer p {
            color: #ffffff;
            font-size: 14px;
            text-align: center;
            margin-bottom: 0;
        }

        /* Tables */

        .table table {
            margin: 30px auto;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .table th {
            border-bottom: 1px solid #edeff2;
            padding-bottom: 8px;
            margin: 0;
        }

        .table td {
            color: #74787e;
            font-size: 15px;
            line-height: 18px;
            padding: 10px 0;
            margin: 0;
        }

        .content-cell {
            padding: 35px;
        }

        .content-cell-footer {
            padding: 15px;
        }

        /* Buttons */

        .action {
            margin: 30px auto;
            padding: 0;
            text-align: center;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .button {
            border-radius: 3px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
            color: #fff;
            display: inline-block;
            text-decoration: none;
            -webkit-text-size-adjust: none;
            width: 252px;
            left: 582px;
            top: 535px;
            font-weight: 500;
            font-size: 18px;
            line-height: 26px;
            text-align: center;
        }

        .button-blue,
        .button-primary {
            background-color: #3490dc;
            border-top: 10px solid #3490dc;
            border-right: 18px solid #3490dc;
            border-bottom: 10px solid #3490dc;
            border-left: 18px solid #3490dc;
        }

        .button-green,
        .button-success {
            background-color: #07D1BF;
            border-top: 10px solid #07D1BF;
            border-right: 18px solid #07D1BF;
            border-bottom: 10px solid #07D1BF;
            border-left: 18px solid #07D1BF;
        }

        .button-red,
        .button-error {
            background-color: #e3342f;
            border-top: 10px solid #e3342f;
            border-right: 18px solid #e3342f;
            border-bottom: 10px solid #e3342f;
            border-left: 18px solid #e3342f;
        }

        /* Panels */

        .panel {
            margin: 0 0 21px;
        }

        .panel-content {
            background-color: #f1f5f8;
            padding: 16px;
        }

        .panel-item {
            padding: 0;
        }

        .panel-item p:last-of-type {
            margin-bottom: 0;
            padding-bottom: 0;
        }

        /* Promotions */

        .promotion {
            background-color: #ffffff;
            border: 2px dashed #9ba2ab;
            margin: 0;
            margin-bottom: 25px;
            margin-top: 25px;
            padding: 24px;
            width: 100%;
            -premailer-cellpadding: 0;
            -premailer-cellspacing: 0;
            -premailer-width: 100%;
        }

        .promotion h1 {
            text-align: center;
        }

        .promotion p {
            font-size: 15px;
            text-align: center;
        }
    </style>
</head>

<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
    <tr>
        <td align="center">
            <table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                <!-- HEADERS -->
                <tr>
                    <td class="header">
                        <p>{{ config('app.name') }}</p>
                    </td>
                </tr>

                <!-- Email Body -->
                <tr>
                    <td class="body" width="100%" cellpadding="0" cellspacing="0">
                        <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <!-- Body content -->
                            <tr>
                                <td class="content-cell">
                                    <p>Hello <strong>{{ $user->last_name . " " . $user->first_name }}</strong>,</p>
                                    <p>Your account has been created. Please activate your account by clicking this
                                        link</p>
                                    <p>
                                        <a href="{{ route('backend::email.verify', ['id' => $user->getKey(), 'hash' => sha1(substr($user->id, 4, 6) . $user->email )]) }}"
                                           class="button button-success" target="_blank">Verify Email</a>
                                    </p>
                                    <p>
                                        <strong>* If the button does not work, please copy this link</strong>
                                    </p>
                                    <p>
                                        <a href="{{ route('backend::email.verify', ['id' => $user->getKey(), 'hash' => sha1(substr($user->id, 4, 6) . $user->email )]) }}"
                                           target="_blank">{{ route('backend::email.verify', ['id' => $user->getKey(), 'hash' => sha1(substr($user->id, 4, 6) . $user->email )]) }}</a>
                                    </p>
                                    <p>Thanks</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- FOOTER -->
                <tr>
                    <td>
                        <table class="footer" align="center" width="100%" cellpadding="0" cellspacing="0"
                               role="presentation">
                            <tr>
                                <td class="content-cell-footer" align="center">
                                    <p>{{ config('app.name') }} &copy;2020 | @lang('All rights reserved.')</p>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>

</html>