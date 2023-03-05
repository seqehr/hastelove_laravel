<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoboPie</title>
    <style>
        body {
            margin: 50px;
        }

        * {
            background-color: cornflowerblue;
        }
    </style>
</head>

<body>
    <center>
        <img src="{{ env('DEFAULT_URL') }}/mbp.png" />

        <hr>
        Here’s your verification code:
        <br>
        {{ $details['body'] }}
        <br>
        Welcome !
        <br>
        You have successfully created the following Mobopie Account:
        <br>
        {{ $details['email'] }}
        <br>
        Sincerely,
        <br>
        The Mobopie media team
        <br>
    </center>
</body>

</html>