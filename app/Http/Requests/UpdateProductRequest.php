<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'name'        => 'required|string|max:255',
			'description' => 'nullable|string|max:1000',
			'price'       => 'required|numeric|min:0.01',
			'stock'       => 'required|integer|min:0',
		];
	}

	public function messages()
	{
		return [
			'name.required'  => 'O nome do produto é obrigatório.',
			'name.string'    => 'O nome deve ser uma sequência de caracteres.',
			'name.max'       => 'O nome não pode exceder 255 caracteres.',
			'description.max'=> 'A descrição não pode exceder 1000 caracteres.',
			'price.required' => 'O preço é obrigatório.',
			'price.numeric'  => 'O preço deve ser um valor numérico.',
			'price.min'      => 'O preço deve ser, no mínimo, 0,01.',
			'stock.required' => 'O estoque é obrigatório.',
			'stock.integer'  => 'O estoque deve ser um número inteiro.',
			'stock.min'      => 'O estoque não pode ser negativo.',
		];
	}
}
