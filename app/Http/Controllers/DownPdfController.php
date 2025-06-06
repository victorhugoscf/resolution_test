<?php

namespace App\Http\Controllers;

use App\Services\SalePdfService;
use Illuminate\Http\Response;

class DownPdfController extends Controller
{
	protected $salePdfService;

	public function __construct(SalePdfService $salePdfService)
	{
		$this->salePdfService = $salePdfService;
	}

	/**
	 * Faz o download do PDF da venda.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function downloadPdf($id): Response
	{
		return $this->salePdfService->downloadSalePdf((int) $id);
	}
}
