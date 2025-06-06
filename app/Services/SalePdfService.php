<?php
namespace App\Services;

use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;

class SalePdfService
{
	/**
	 * Gera a instância de PDF para uma venda específica.
	 *
	 * @param  int  $id
	 * @return \Barryvdh\DomPDF\PDF
	 *
	 * @throws ModelNotFoundException
	 */
	public function generateSalePdf(int $id)
	{
		$sale = Sale::with([
			'client',
			'products',
			'payment',
			'installmentSales',
		])->findOrFail($id);

		return Pdf::loadView('pdf.sale', [
			'sale' => $sale,
		]);
	}

	/**
	 * Retorna o Response para download do PDF da venda.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 *
	 * @throws ModelNotFoundException
	 */
	public function downloadSalePdf(int $id): Response
	{
		$pdf   = $this->generateSalePdf($id);
		$file  = 'venda_' . $id . '.pdf';
		return $pdf->download($file);
	}
}
