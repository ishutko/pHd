@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Calculation Results</h1>

        <h2 class="mb-4">Framework: <span class="text-primary">{{ $result['framework'] }}</span></h2>

        <table class="table table-bordered table-striped shadow-sm">
            <thead class="table-dark">
            <tr>
                <th>Parameter</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>Number of Classes (NOC)</td>
                <td>{{ $result['noc'] }}</td>
            </tr>
            <tr>
                <td>Average Methods per Class (MbC)</td>
                <td>{{ $result['mbc'] }}</td>
            </tr>
            <tr>
                <td>Average DIT per Class</td>
                <td>{{ $result['dit'] }}</td>
            </tr>
            <tr>
                <td>Confidence Probability</td>
                <td>{{ $result['confidence'] }}%</td>
            </tr>
            <tr>
                <td>Regression Result</td>
                <td>{{ $result['regressionResult'] }}</td>
            </tr>
            <tr>
                <td>Size (KLOC)</td>
                <td>{{ $result['kloc'] }}</td>
            </tr>
            <tr>
                <td>Confidence Interval (Lower)</td>
                <td>{{ $result['confidenceInterval']['lower'] }}</td>
            </tr>
            <tr>
                <td>Confidence Interval (Upper)</td>
                <td>{{ $result['confidenceInterval']['upper'] }}</td>
            </tr>
            <tr>
                <td>Prediction Interval (Lower)</td>
                <td>{{ $result['predictionInterval']['lower'] }}</td>
            </tr>
            <tr>
                <td>Prediction Interval (Upper)</td>
                <td>{{ $result['predictionInterval']['upper'] }}</td>
            </tr>
            <tr>
                <td>MMRE</td>
                <td>{{ $result['metrics']['MMRE'] }}</td>
            </tr>
            <tr>
                <td>PRED(0.25)</td>
                <td>{{ $result['metrics']['PRED(0.25)'] ? 'Yes' : 'No' }}</td>
            </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="{{ route('calculation.form') }}" class="btn btn-primary">Go Back</a>
        </div>
    </div>
@endsection
