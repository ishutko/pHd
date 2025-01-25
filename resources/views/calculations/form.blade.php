@extends('layouts.app')

@section('content')
    <h1>Calculation Form</h1>

    <form method="POST" action="{{ route('calculate') }}">
        @csrf

        <div>
            <label for="framework">Select Framework:</label>
            <select name="framework" id="framework" required>
                @foreach ($frameworks as $framework)
                    <option value="{{ $framework }}">{{ $framework }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="noc">Number of Classes (NOC):</label>
            <input type="number" name="noc" id="noc" required>
        </div>

        <div>
            <label for="mbc">Methods per Class (MbC):</label>
            <input type="number" step="0.1" name="mbc" id="mbc" required>
        </div>

        <div>
            <label for="dit">Average DIT per Class:</label>
            <input type="number" step="0.1" name="dit" id="dit" required>
        </div>

        <div>
            <label for="confidence">Confidence Probability (%):</label>
            <input type="number" name="confidence" id="confidence" required>
        </div>

        <button type="submit">Calculate</button>
    </form>

    <button id="generate-random">Generate Random Values</button>
    <button id="load-example">Load Example Data</button>

    <script>
        // Generate random values
        document.getElementById('generate-random').addEventListener('click', function () {
            const framework = document.getElementById('framework').value;

            fetch(`{{ route('generate-random') }}?framework=${framework}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('noc').value = data.noc;
                    document.getElementById('mbc').value = data.mbc;
                    document.getElementById('dit').value = data.dit;
                    document.getElementById('confidence').value = data.confidence;
                });
        });

        // Load example data
        document.getElementById('load-example').addEventListener('click', function () {
            const framework = document.getElementById('framework').value;

            fetch(`{{ route('get-example-data') }}?framework=${framework}`)
                .then(response => response.json())
                .then(data => {
                    if (Array.isArray(data) && data.length > 0) {
                        const example = data[0]; // Load the first example
                        document.getElementById('noc').value = example.X1;
                        document.getElementById('mbc').value = example.X2;
                        document.getElementById('dit').value = example.X3;
                        document.getElementById('confidence').value = 90; // Default confidence
                    } else {
                        alert('No example data available.');
                    }
                });
        });
    </script>
@endsection
