<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Application')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Подключение CSS -->
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="{{ route('calculation.form') }}">Home</a></li>
            <li><a href="#">About</a></li>
            <li><a href="#">Contact</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="container">
        @yield('content') <!-- Основной контент -->
    </div>
</main>

<footer>
    <p>&copy; {{ date('Y') }} Your Application. All rights reserved.</p>
</footer>

<script src="{{ asset('js/app.js') }}"></script> <!-- Подключение JS -->
</body>
</html>
