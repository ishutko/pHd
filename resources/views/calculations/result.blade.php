@extends('layouts.app')

@section('content')
    <h1>Calculation Results</h1>

    <h2>Framework: {{ $result['framework'] }}</h2>

    <table border="1" cellpadding="10">
        <tr>
            <th>Parameter</th>
            <th>Value</th>
        </tr>
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
            <td>Prediction Interval (Lower Bound)</td>
            <td>{{ $result['predictionInterval']['lower'] }}</td>
        </tr>
        <tr>
            <td>Prediction Interval (Upper Bound)</td>
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
    </table>

    <a href="{{ route('calculation.form') }}">Go Back</a>
@endsection
