<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstimationRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Разрешаем доступ
    }

    public function rules()
    {
        return [
            'noc' => 'required|integer|min:1', // Количество классов
            'mbc' => 'required|numeric', // Среднее количество методов
            'dit' => 'required|numeric', // Глубина дерева наследования
            'confidence' => 'required|numeric|min:0|max:1', // Доверительная вероятность
        ];
    }

    public function messages()
    {
        return [
            'noc.required' => 'Количество классов обязательно для заполнения.',
            'noc.integer' => 'Количество классов должно быть целым числом.',
            'noc.min' => 'Количество классов должно быть не менее 1.',
            'mbc.required' => 'Среднее количество методов обязательно для заполнения.',
            'mbc.numeric' => 'Среднее количество методов должно быть числом.',
            'dit.required' => 'Глубина дерева наследования обязательна для заполнения.',
            'dit.numeric' => 'Глубина дерева наследования должна быть числом.',
            'confidence.required' => 'Доверительная вероятность обязательна для заполнения.',
            'confidence.numeric' => 'Доверительная вероятность должна быть числом.',
            'confidence.min' => 'Доверительная вероятность должна быть не менее 0.',
            'confidence.max' => 'Доверительная вероятность должна быть не более 1.',
        ];
    }
}
