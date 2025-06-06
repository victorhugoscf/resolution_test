<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		$clientId = $this->route('client') ?? $this->route('id') ?? null;

		return [
			'name'     => 'required|string|max:255',
			'email'    => "nullable|email|unique:clients,email,{$clientId}",
			'phone'    => 'nullable|string|max:20',
			'document' => "nullable|string|max:20|unique:clients,document,{$clientId}",
			'address'  => 'nullable|string|max:500',
		];
	}

	public function messages()
	{
		return [
			'name.required'   => 'O campo Nome é obrigatório.',
			'name.string'     => 'O campo Nome deve ser uma sequência de caracteres válida.',
			'name.max'        => 'O campo Nome não pode ter mais de 255 caracteres.',

			'email.email'     => 'Por favor, insira um endereço de e-mail válido.',
			'email.unique'    => 'O endereço de e-mail informado já está em uso.',

			'phone.string'    => 'O campo Telefone deve ser uma sequência de caracteres válida.',
			'phone.max'       => 'O campo Telefone não pode ter mais de 20 caracteres.',

			'document.string' => 'O campo Documento deve ser uma sequência de caracteres válida.',
			'document.max'    => 'O campo Documento não pode ter mais de 20 caracteres.',
			'document.unique' => 'O número de Documento informado já está cadastrado.',

			'address.string'  => 'O campo Endereço deve ser uma sequência de caracteres válida.',
			'address.max'     => 'O campo Endereço não pode ter mais de 500 caracteres.',
		];
	}
}
