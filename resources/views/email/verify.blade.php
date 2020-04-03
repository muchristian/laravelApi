<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
<div class="verify-div">
<h2>Hi {{ $firstName }},</h2>
<p>Thank you for creating an account with us. Don't forget to complete your registration!.
<br>
<br>
Please click on the link below or copy it into the address bar of your browser to confirm your email address:</p>
<a href="{{ url('user/verify', $verification_code)}}">verify email</a>
</div>
</body>
</html>