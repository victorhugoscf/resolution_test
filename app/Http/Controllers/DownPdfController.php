<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DownPdfController extends Controller
{
    public function downloadPdf($id)
    {
        $sale = Sale::with(['client', 'products', 'payment', 'installments'])->findOrFail($id);
        $pdf = Pdf::loadView('pdf.sale', [
            'sale' => $sale,
        ]);

        return $pdf->download('venda_' . $sale->id . '.pdf');
    }
}