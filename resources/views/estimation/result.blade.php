<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.results') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>{{ __('messages.results') }}</h1>
    <div class="card">
        <div class="card-header">
            {{ __('messages.estimations') }}
        </div>
        <div class="card-body">
            <p>
                <strong>{{ __('messages.size') }}</strong>
                &nbsp;
                {{ number_format($result['size'], 4) }} KLOC
            </p>
            <p>
                <strong>{{ __('messages.confidence_interval') }}</strong>
                &nbsp;
                {{ number_format($result['confidence_interval'][0], 4) }}
                &nbsp;-&nbsp;
                {{ number_format($result['confidence_interval'][1], 4) }}
            </p>
            <p>
                <strong>{{ __('messages.confidence_interval') }}</strong>
                &nbsp;
                {{ number_format($result['prediction_interval'][0], 4) }}
                &nbsp;-&nbsp;
                {{ number_format($result['prediction_interval'][1], 4) }}
            </p>
        </div>
    </div>
    <a href="{{ route('form') }}" class="btn btn-primary mt-3">{{ __('messages.return_home') }}</a>
</div>
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
