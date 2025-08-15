<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        /* Hide interactive elements only on the welcome page */
        .welcome-page a,
        .welcome-page button,
        .welcome-page input {
            display: none !important;
        }
    </style>
</head>
<body class="antialiased welcome-page">
    <div class="container mx-auto py-10 text-center">
        <h1 class="text-3xl font-bold">Welcome</h1>
        <p class="mt-4">Your application is up and running.</p>

    <!-- Links removed: welcome page intentionally clean -->
    </div>
</body>
</html>
