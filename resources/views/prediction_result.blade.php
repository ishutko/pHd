<style>
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 20px;
    }
    h1 {
        color: #2c3e50;
    }
    h2 {
        color: #2980b9;
    }
    p {
        margin-bottom: 10px;
    }
    .form {
        padding: 20px;
        margin: auto 20%;
        border: 1px solid black;
        border-radius: 10px;
        background-color: #ccc;
    }
</style>

<div class="form">
    <h1>Результати передбачення</h1>
    <p>Передбачене значення Y (розмір ПЗ в KLOC): <b>{{ $predictedY }}</b></p>
    <p>Відстань Махаланобіса: <b>{{ $mahalanobisDistance }}</b></p>
    <p>Довірчий інтервал: від <b>{{ $confidenceInterval[0] }}</b> до <b>{{ $confidenceInterval[1] }}</b></p>
    <p>Інтервал передбачення: від <b>{{ $predictionInterval[0] }}</b> до <b>{{ $predictionInterval[1] }}</b></p>
</div>
