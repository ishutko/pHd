<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Application')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
          rel="stylesheet"
          crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Подключение CSS -->
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Home</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            @yield('content') <!-- Основной контент -->
        </div>
    </div>
</div>

<footer class="text-center py-3 bg-dark text-white mt-5">
    <div class="container">
        <p class="mb-0">&copy; 2024-{{ date('Y') }} Оцінка веб-додатків | Фреймворки: Yii, CakePHP, CodeIgniter</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76Aq2kCZqG0XIaG0gGxvLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmY"
        crossorigin="anonymous"></script>
<script src="{{ asset('js/app.js') }}"></script> <!-- Подключение JS -->
</body>
</html>
