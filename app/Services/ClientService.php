<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ClientService
{
	/**
	 * Retorna uma lista paginada de clientes, aplicando filtro de busca se existir.
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
	 */
	public function getClients(Request $request)
	{
		$query = Client::query();

		if ($request->filled('search')) 
		{
			$search = $request->input('search');
			$query->where(function ($q) use ($search) 
			{
				$q->where('name', 'like', "%{$search}%")
					->orWhere('email', 'like', "%{$search}%")
					->orWhere('document', 'like', "%{$search}%");
			});
		}

		return $query->latest()->paginate(10);
	}

	/**
	 * Retorna uma nova instância de Client para ser usada em formulários de criação.
	 *
	 * @return Client
	 */
	public function createClient(): Client
	{
		return new Client();
	}

	/**
	 * Valida e armazena um novo cliente no banco.
	 *
	 * @param  Request  $request
	 * @return Client
	 *
	 * @throws ValidationException
	 */
    public function storeClient(array $data): Client
    {
			return Client::create($data);
    }

	/**
	 * Encontra um cliente pelo ID ou lança ModelNotFoundException.
	 *
	 * @param  int  $id
	 * @return Client
	 */
	public function findClient(int $id): Client
	{
		return Client::findOrFail($id);
	}

	/**
	 * Valida e atualiza os dados de um cliente existente.
	 *
	 * @param  Request  $request
	 * @param  int      $id
	 * @return Client
	 *
	 * @throws ValidationException
	 */
	public function updateClient(int $id, array $data): Client
	{
		$client = $this->findClient($id);
		$client->update($data);
		return $client;
	}

	/**
	 * Exclui um cliente pelo ID ou lança ModelNotFoundException.
	 *
	 * @param  int  $id
	 * @return void
	 */
	public function deleteClient(int $id): void
	{
		$client = $this->findClient($id);
		$client->delete();
	}
}
