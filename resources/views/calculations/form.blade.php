@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Calculation Form</h1>

        <form method="POST" action="{{ route('calculate') }}" class="p-4 border rounded shadow-sm bg-light">
            @csrf

            <div class="mb-3">
                <label for="framework" class="form-label">Select Framework:</label>
                <select name="framework" id="framework" class="form-select" required>
                    @foreach ($frameworks as $framework)
                        <option value="{{ $framework }}">{{ $framework }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="noc" class="form-label">Number of Classes (NOC):</label>
                <input type="number" name="noc" id="noc" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="mbc" class="form-label">Methods per Class (MbC):</label>
                <input type="number" step="0.01" name="mbc" id="mbc" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="dit" class="form-label">Average DIT per Class:</label>
                <input type="number" step="0.01" name="dit" id="dit" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="confidence" class="form-label">Confidence Probability (%):</label>
                <input type="number" name="confidence" id="confidence" class="form-control" required>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Calculate</button>
                <button type="button" id="generate-random" class="btn btn-warning">Generate Random Values</button>
                <button type="button" id="load-example" class="btn btn-secondary">Load Example Data</button>
            </div>
        </form>
    </div>

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
