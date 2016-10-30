<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Activate Account</title>
</head>

<body>
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%">
    <tbody>
    <tr>
        <td valign="top">

            <table border="0" cellpadding="2" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td style="text-align:center;">
                        <img src="{{ (getenv('APP_ENV') != 'local') ? getenv('SITE_URL') : 'http://laravel-library.amasik.com' }}/img/logo.png" />
                    </td>
                </tr>
                <tr>
                    <td valign="top">

                        <p>Hi {{$first_name . ' ' . $last_name}},</p>

                        <p><strong>Welcome to {{ config('app.site_name') }}</strong></p>

                        <p>Get Started! You're just one step away. Activate your account by clicking the link below</p>

                        <p>
                            <strong>Email</strong>   : {{ $email }} <br />
                            <strong>Password</strong>: {{ $password }} <br />
                        </p>

                        <p style="text-align: center;"><a href="{{$activation_url}}" style="text-decoration:none;color: #FFF;background-color: #6fb0b7;padding:10px 20px;font-weight:bold;margin: 20px 10px 20px 0;text-align:center;cursor:pointer;display: inline-block;border-radius: 25px;">Activate Your Account</a>
                        </p>

                        <p>Copyright © {{date('Y')}} {{ config('app.site_name') }} - All rights reserved.</p>
                    </td>
                </tr>
                </tbody>
            </table>

        </td>
    </tr>
    </tbody>
</table>
</body>

</html>