<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSaleRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'client_id'                   => 'required|exists:clients,id',
			'sale_items'                  => 'required|array|min:1',
			'sale_items.*.product_id'     => 'required|exists:products,id',
			'sale_items.*.quantity'       => 'required|integer|min:1',
			'sale_items.*.unit_price'     => 'required|numeric|min:0.01',
			'sale_items.*.subtotal'       => 'required|numeric|min:0.01',
			'sales'                       => 'required|array',
			'sales.*.total_amount'        => 'required|numeric|min:0.01',
			'sales.*.status'              => 'required|in:pending,completed,canceled',
			'payments'                    => 'required|array|min:1',
			'payments.*.method'           => 'required|in:cash,credit,debit,pix',
			'payments.*.total_amount'     => 'required|numeric|min:0.01',
			'payments.*.installments'     => 'required_if:payments.*.method,credit|integer|min:1|max:12',
		];
	}

	public function messages()
	{
		return [
			'client_id.required'               => 'O campo cliente é obrigatório.',
			'sale_items.required'              => 'É obrigatório enviar ao menos um item de venda.',
			'payments.*.method.in'             => 'Método de pagamento inválido.',
		];
	}
}
