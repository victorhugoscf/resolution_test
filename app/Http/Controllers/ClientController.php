<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
	public function index()
	{
		$query = Client::query();

		if (request()->has('search') && request()->filled('search')) 
		{
			$search = request('search');
			$query->where(function ($q) use ($search) 
			{
				$q->where('name', 'like', "%$search%")
				->orWhere('email', 'like', "%$search%")
				->orWhere('document', 'like', "%$search%");
			});
		}

		$clients = $query->latest()->paginate(10);

		if (request()->ajax()) {
			return response()->view('clients._Table', compact('clients'));
		}
		return view('clients.ClientList', compact('clients'));
	}
	
	public function create()
	{
		return view('clients.NewClient', ['client' => new Client()]);
	}

	public function store(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'nullable|email|unique:clients,email',
			'phone' => 'nullable|string|max:20',
			'document' => 'nullable|string|max:20|unique:clients,document',
			'address' => 'nullable|string|max:500',
		]);

		Client::create($request->all());

		return redirect()->route('clients.index')->with('success', 'Cliente cadastrado com sucesso!');
	}

	public function edit($id)
	{
		$client = Client::findOrFail($id);
		return view('clients.form', compact('client'));
	}

	public function update(Request $request, $id)
	{
		$client = Client::findOrFail($id);

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'nullable|email|unique:clients,email,' . $client->id,
			'phone' => 'nullable|string|max:20',
			'document' => 'nullable|string|max:20|unique:clients,document,' . $client->id,
			'address' => 'nullable|string|max:500',
		]);

		$client->update($request->all());

		return redirect()->route('clients.list')->with('success', 'Cliente atualizado com sucesso!');
	}

	public function destroy($id)
	{
		$client = Client::findOrFail($id);
		$client->delete();
		return redirect()->route('clients.list')->with('success', 'Cliente excluído com sucesso!');
	}
}
