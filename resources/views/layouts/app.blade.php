<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Application')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KyZXEAg3QhqLMpG8r+Knujsl5+5hb7mr+2BR5UT/mYk5FfoYo0jw+eoM/eN65Z5" crossorigin="anonymous">
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


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76Aq2kCZqG0XIaG0gGxvLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmY"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}"></script> <!-- Подключение JS -->
</body>
</html>
