<?php

namespace App\Http\Controllers;

use App\Services\ClientService;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Http\Request;

class ClientController extends Controller
{
	protected $clientService;

	public function __construct(ClientService $clientService)
	{
		$this->clientService = $clientService;
	}

	public function index(Request $request)
	{
		$clients = $this->clientService->getClients($request);

		if ($request->ajax()) 
		{
			return response()->view('clients._table', compact('clients'));
		}

		return view('clients.ClientList', compact('clients'));
	}

	public function create()
	{
		$client = $this->clientService->createClient();
		return view('clients.NewClient', compact('client'));
	}

	public function store(StoreClientRequest $request)
	{
		$this->clientService->storeClient($request->validated());
		return redirect()->route('clients.index')
										 ->with('success', 'Cliente cadastrado com sucesso!');
	}

	public function edit($id)
	{
		$client = $this->clientService->findClient($id);
		return view('clients.form', compact('client'));
	}

	public function update(UpdateClientRequest $request, $id)
	{
		$this->clientService->updateClient($request->validated(), $id);
		return redirect()->route('clients.list')
										 ->with('success', 'Cliente atualizado com sucesso!');
	}

	public function destroy($id)
	{
		$this->clientService->deleteClient($id);
		return redirect()->route('clients.list')
										 ->with('success', 'Cliente excluído com sucesso!');
	}
}
