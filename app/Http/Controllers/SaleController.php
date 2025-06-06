<?php

namespace App\Http\Controllers;

use App\Services\SaleService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;

class SaleController extends Controller
{
	/** @var SaleService */
	protected $saleService;

	public function __construct(SaleService $saleService)
	{
			$this->saleService = $saleService;
	}

	/**
	 * Lista todas as vendas
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return $this->saleService->index();
	}

	/**
	 * Formulário de criação de venda
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->saleService->create();
	}

	/**
	 * Processa a criação de uma venda
	 *
	 * @param  Request  $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(StoreSaleRequest $request)
	{
		return $this->saleService->store($request->validated());
	}

	/**
	 * Formulário de edição de venda
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit($id)
	{
		return $this->saleService->edit($id);
	}

	/**
	 * Processa a atualização de uma venda
	 *
	 * @param  Request  $request
	 * @param  int      $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(UpdateSaleRequest $request, int $id)
	{
		return $this->saleService->update($request->validated(), $id);
	}

	/**
	 * Exclui uma venda
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy($id)
	{
		return $this->saleService->destroy($id);
	}
}
