<?php

namespace App\Http\Controllers;

use App\Models\Estimator;
use Illuminate\Http\Request;

// Это модель, где будет находиться логика расчетов

class EstimationController extends Controller
{
    public function index()
    {
        return view('estimation.form');
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'class' => 'required|numeric|min:0',
            'methods' => 'required|numeric|min:0',
            'dit' => 'required|numeric|min:0',
            'confidence' => 'required|numeric|between:0,1',
        ]);

        $estimator = new Estimator($request->all());
        $result = $estimator->calculateEstimations();

        return view('estimation.result', ['result' => $result]);
    }
}
