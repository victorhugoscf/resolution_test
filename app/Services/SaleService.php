<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\InstallmentSale;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleService
{
	/**
	 * Retorna a view da lista de vendas (mesma lógica de SaleController@index).
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		$sales = Sale::with(['client', 'user', 'products', 'payment'])
									->latest()
									->paginate(10);

		return view('sales.List', compact('sales'));
	}

	/**
	 * Retorna a view de criação (mesma lógica de SaleController@create).
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		$clients  = Client::all();
		$products = Product::all();
		return view('sales.Create', compact('clients', 'products'), ['sale' => new Sale()]);
	}

	/**
	 * Processa a criação de uma venda **com dados já validados**.
	 *
	 * @param  array  $validated
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(array $validated)
	{
		
		if (isset($validated['sale_items'])) 
		{
			foreach ($validated['sale_items'] as &$item) 
			{
				$item['unit_price'] = str_replace(',', '.', $item['unit_price']);
				$item['subtotal']   = str_replace(',', '.', $item['subtotal']);
			}
			unset($item);
		}

		if (isset($validated['sales'])) 
		{
			foreach ($validated['sales'] as &$sale) 
			{
				$sale['total_amount'] = str_replace(',', '.', $sale['total_amount']);
			}
			unset($sale);
		}

		if (isset($validated['payments'])) 
		{
			foreach ($validated['payments'] as &$payment) 
			{
				$amount = isset($payment['total_amount'])
					? $payment['total_amount']
					: (isset($payment['amount']) ? $payment['amount'] : '0');

				$payment['total_amount'] = str_replace(',', '.', $amount);
				$payment['installments'] = isset($payment['installments'])
					? (int) $payment['installments']
					: 1;

				unset($payment['amount']);
			}
			unset($payment);
		}

		try {
			DB::beginTransaction();

			$sale = Sale::create([
				'client_id'         => $validated['client_id'],
				'user_id'           => Auth::id(),
				'parent_sale_id'    => null,
				'total_amount'      => $validated['sales'][0]['total_amount'],
				'installment_number'=> null,
				'amount'            => null,
				'due_date'          => null,
				'status'            => $validated['sales'][0]['status'],
				'sale_date'         => now(),
			]);

			foreach ($validated['sale_items'] as $item) 
			{
				SaleItem::create([
					'sale_id'    => $sale->id,
					'product_id' => $item['product_id'],
					'quantity'   => $item['quantity'],
					'unit_price' => $item['unit_price'],
					'subtotal'   => $item['subtotal'],
				]);
			}

			$totalPaid = 0;
			foreach ($validated['payments'] as $payment) 
			{
				$totalPaid += $payment['total_amount'];
				Payment::create([
					'sale_id'      => $sale->id,
					'method'       => $payment['method'],
					'total_amount' => $payment['total_amount'],
				]);

				if ($payment['method'] === 'credit' && $payment['installments'] > 1) 
				{
					$installmentAmount = $payment['total_amount'] / $payment['installments'];
					for ($i = 1; $i <= $payment['installments']; $i++) 
					{
						InstallmentSale::create([
							'sale_id'            => $sale->id,
							'installment_number' => $i,
							'amount'             => round($installmentAmount, 2),
							'due_date'           => Carbon::now()->addMonths($i)->startOfDay(),
							'status'             => 'pending',
						]);
					}
				}
			}

			if (
					abs($totalPaid - $validated['sales'][0]['total_amount']) > 0.01
					&& $validated['sales'][0]['status'] === 'completed'
			) {
					throw new \Exception(
						'Total paid (' .
						number_format($totalPaid, 2, ',', '.') .
						') must match total amount (' .
						number_format($validated['sales'][0]['total_amount'], 2, ',', '.') .
						') for completed sales.'
					);
			}

			DB::commit();
			return redirect()->route('sales.list')
												->with('success', 'Venda cadastrada com sucesso!');
		} catch (\Exception $e) {
				DB::rollBack();
				return back()
				->withErrors(['error' => 'Erro ao cadastrar venda: ' . $e->getMessage()])
				->withInput();
		}
	}

	/**
	 * Retorna a view de edição (mesma lógica de SaleController@edit).
	 *
	 * @param  int  $id
	 * @return \Illuminate\View\View
	 */
	public function edit(int $id)
	{
		$sale     = Sale::with(['saleItems', 'payment'])->findOrFail($id);
		$clients  = Client::all();
		$products = Product::all();
		return view('sales.Edit', compact('sale', 'clients', 'products'));
	}

	/**
	 * Processa a atualização de uma venda **com dados já validados**.
	 *
	 * @param  array  $validated
	 * @param  int    $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update(array $validated, int $id)
	{
		if (isset($validated['sale_items'])) 
		{
			foreach ($validated['sale_items'] as &$item) 
			{
				$item['unit_price'] = str_replace(',', '.', $item['unit_price']);
				$item['subtotal']   = str_replace(',', '.', $item['subtotal']);
			}
			unset($item);
		}

		if (isset($validated['sales'])) 
		{
			foreach ($validated['sales'] as &$sale) 
			{
				$sale['total_amount'] = str_replace(',', '.', $sale['total_amount']);
			}
			unset($sale);
		}

		if (isset($validated['payments'])) 
		{
			foreach ($validated['payments'] as &$payment) 
			{
				$amount = isset($payment['total_amount'])
					? $payment['total_amount']
					: (isset($payment['amount']) ? $payment['amount'] : '0');

				$payment['total_amount'] = str_replace(',', '.', $amount);
				$payment['installments'] = isset($payment['installments'])
					? (int) $payment['installments']
					: 1;

				unset($payment['amount']);
			}
			unset($payment);
		}

		try {
			DB::beginTransaction();

			$sale = Sale::findOrFail($id);
			$sale->update([
				'client_id'    => $validated['client_id'],
				'user_id'      => Auth::id(),
				'total_amount' => $validated['sales'][0]['total_amount'],
				'status'       => $validated['sales'][0]['status'],
				'sale_date'    => now(),
			]);

			$sale->saleItems()->delete();
			foreach ($validated['sale_items'] as $item) 
			{
				SaleItem::create([
					'sale_id'    => $sale->id,
					'product_id' => $item['product_id'],
					'quantity'   => $item['quantity'],
					'unit_price' => $item['unit_price'],
					'subtotal'   => $item['subtotal'],
				]);
			}

			$sale->payment()->delete();
			$sale->installmentSales()->delete();

			$totalPaid = 0;
			foreach ($validated['payments'] as $payment) 
			{
					$totalPaid += $payment['total_amount'];

					Payment::create([
						'sale_id'      => $sale->id,
						'method'       => $payment['method'],
						'total_amount' => $payment['total_amount'],
					]);

					if ($payment['method'] === 'credit' && $payment['installments'] > 1) 
					{
						$installmentAmount = $payment['total_amount'] / $payment['installments'];
						for ($i = 1; $i <= $payment['installments']; $i++) 
						{
							InstallmentSale::create([
								'sale_id'            => $sale->id,
								'installment_number' => $i,
								'amount'             => round($installmentAmount, 2),
								'due_date'           => Carbon::now()->addMonths($i)->startOfDay(),
								'status'             => 'pending',
							]);
						}
					}
			}

			if (
					abs($totalPaid - $validated['sales'][0]['total_amount']) > 0.01
					&& $validated['sales'][0]['status'] === 'completed'
			) {
					throw new \Exception(
						'Total paid (' .
						number_format($totalPaid, 2, ',', '.') .
						') must match total amount (' .
						number_format($validated['sales'][0]['total_amount'], 2, ',', '.') .
						') for completed sales.'
					);
			}

			DB::commit();
			return redirect()->route('sales.list')
												->with('success', 'Venda atualizada com sucesso!');
		} catch (\Exception $e) {
			DB::rollBack();
			\Log::error('Sale update failed', [
				'error' => $e->getMessage(),
				'data'  => $validated,
			]);

			return back()
				->withErrors(['error' => 'Erro ao atualizar venda: ' . $e->getMessage()])
				->withInput();
		}
	}

	/**
	 * Exclui a venda (mesma lógica de SaleController@destroy).
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(int $id)
	{
		$sale = Sale::findOrFail($id);
		$sale->delete();
		return redirect()->route('sales.list')->with('success', 'Venda excluída com sucesso!');
	}
}
