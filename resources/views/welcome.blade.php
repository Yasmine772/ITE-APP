<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <style>
        body {
            font-family: 'Figtree', sans-serif;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #f8fafc;
            color: #1a202c;
        }
        .container {
            text-align: center;
        }
        .title {
            font-size: 2rem;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="title">
        Laravel {{ Illuminate\Foundation\Application::VERSION }}
    </div>
</div>
</body>
</html>
