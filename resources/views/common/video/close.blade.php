<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta content="width=device-width,initial-scale=1" name="viewport">
    <title>{{ config('app.name') }}</title>
    <style>
        body,
        html {
            background-color: #fff;
            color: #636b6f;
            font-family: Console, monospace;
            font-weight: 200;
            height: 100vh;
            margin: 0
        }

        .full-height {
            height: 100vh
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center
        }

        .content {
            text-align: center
        }

        .title {
            font-size: 84px
        }

        .blue {
            background-color: #008CBA
        }

        .red {
            background-color: #f44336
        }

        button:hover,
        a:hover {
            background-color: #ddd;
            color: #333
        }

        button,
        a {
            cursor: pointer;
            background-color: #555;
            border: none;
            color: #fff;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 4px;
            -webkit-transition-duration: .4s;
            transition-duration: .4s
        }
    </style>
</head>

<body>
    <div class="flex-center full-height position-ref">
        <div class="content">
            <div class="m-b-md title">
                <span style="letter-spacing: -16px;">¯\_(ツ)_/¯</span>
            </div>
            <div class="details">
                <h3>Thanks for using {{ config('app.name') }}!</h3>
                <button type="submit" onclick="parent.window.close()" class="red" >Close Window</button>
            </div>
        </div>
    </div>
</body>

</html>