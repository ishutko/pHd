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
    .alert {
        font-weight: bold;
    }
    .alert-danger {
        color: red;
    }
</style>

<div class="form">
    <form action="/prediction" method="POST">
        @csrf

        <div>
            <label>Виберіть дослідження:</label>
            <select name="research_model">
                @foreach($models as $model)
                    <option @if($selectedModel === $model) selected @endif value="{{ $model }}">Дослідження {{ $model }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Кількість класів:</label>
            <input type="number" name="number_of_classes" required value="{{ $random['number_of_classes']??'' }}">
        </div>
        <div>
            <label>Середня кількість методів на клас:</label>
            <input type="number" name="average_methods" required value="{{ $random['average_methods']??'' }}">
        </div>
        <div>
            <label>Середнє значення DIT на клас:</label>
            <input type="number" name="average_dit" required value="{{ $random['average_dit']??'' }}">
        </div>
        <div>
            <label>Довірча ймовірність (%):</label>
            <input type="number" step="0.01" name="confidence_level" required value="{{ $random['confidence_level']??'' }}">
        </div>
        <div>
            <button type="submit">Передбачити</button>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </form>
</div>
<p></p>
<div class="form">
    <div>
        Заповнити форму випадковими значеннями:
    </div>
    @foreach($models as $model)
        <div>
            <a href="?model={{ $model }}">
                @if($selectedModel === $model)
                    <b>{{ $model }}</b>
                @else
                    {{ $model }}
                @endif
            </a>
        </div>
    @endforeach

</div>

<h1>Анотація до програми</h1>

<p>Дана програма призначена для оцінки розміру програмного забезпечення на основі кількісних характеристик, таких як кількість класів, середня кількість методів на клас та середнє значення глибини дерева успадкування (DIT). Програма використовує моделі регресії, розроблені на основі досліджень, таких як дослідження CakePHP, і дозволяє вибирати моделі для розрахунку.</p>

<h2>Основні функції:</h2>

<p><strong>1. Введення даних через форму:</strong><br>
    Користувачі можуть вводити кількість класів, середню кількість методів на клас та середнє значення DIT на клас, а також вибирати рівень довірчої ймовірності. Доступний вибір моделі дослідження для розрахунку, що дозволяє розширювати програму для підтримки кількох різних моделей досліджень.</p>

<p><strong>2. Розрахунок розміру програмного забезпечення:</strong><br>
    На основі введених даних програма використовує модель регресії для передбачення розміру програмного забезпечення (вимірюваного в KLOC — тисячі рядків коду). Для перевірки введених даних використовується розрахунок відстані Махаланобіса. Якщо відстань перевищує допустимий поріг ({{ $kvantil }}), введені дані вважаються некоректними.</p>

<p><strong>3. Розрахунок довірчого інтервалу та інтервалу передбачення:</strong><br>
    Програма розраховує довірчі інтервали для передбаченого значення на основі обраної довірчої ймовірності. Розраховуються також інтервали передбачення для оцінки можливих варіацій значень.</p>

<p><strong>4. Підтримка кількох моделей досліджень:</strong><br>
    Програма побудована з можливістю легкої інтеграції нових моделей досліджень. Додаткові дослідження можуть бути додані через створення відповідних моделей із власними середніми значеннями, коваріаційними матрицями та коефіцієнтами регресії.</p>

<p><strong>5. Генерація випадкових даних:</strong><br>
    Програма підтримує функцію автоматичної генерації випадкових даних для полів форми. Ці дані генеруються в межах допустимих діапазонів на основі середніх значень дослідження.</p>

<h2>Потенційне використання:</h2>
<p>Ця програма може використовуватися в якості інструменту для ранньої оцінки розміру програмного забезпечення, особливо для проєктів, де важливо передбачити необхідні ресурси на етапах проектування. Програма також може бути корисною для наукових досліджень, пов'язаних з аналізом моделей програмного забезпечення та прогнозуванням його розміру.</p>
