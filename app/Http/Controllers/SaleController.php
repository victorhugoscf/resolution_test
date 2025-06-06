<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Payment;
use App\Models\InstallmentSale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['client', 'user', 'products','payment'])->latest()->paginate(10);
        return view('sales.list', compact('sales'));
    }

    public function create()
    {
        $clients = Client::all();
        $products = Product::all();
        return view('sales.create', compact('clients', 'products'), ['sale' => new Sale()]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (isset($data['sale_items'])) {
            foreach ($data['sale_items'] as &$item) {
                $item['unit_price'] = str_replace(',', '.', $item['unit_price']);
                $item['subtotal'] = str_replace(',', '.', $item['subtotal']);
            }
        }
        if (isset($data['sales'])) {
            foreach ($data['sales'] as &$sale) {
                $sale['total_amount'] = str_replace(',', '.', $sale['total_amount']);
            }
        }
        if (isset($data['payments'])) {
            foreach ($data['payments'] as &$payment) {
                $amount = isset($payment['total_amount']) ? $payment['total_amount'] : (isset($payment['amount']) ? $payment['amount'] : '0');
                $payment['total_amount'] = str_replace(',', '.', $amount);
                $payment['installments'] = isset($payment['installments']) ? (int) $payment['installments'] : 1;
                unset($payment['amount']);
            }
        }

        $validated = validator($data, [
            'client_id' => 'required|exists:clients,id',
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0.01',
            'sale_items.*.subtotal' => 'required|numeric|min:0.01',
            'sales' => 'required|array',
            'sales.*.total_amount' => 'required|numeric|min:0.01',
            'sales.*.status' => 'required|in:pending,completed,canceled',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:cash,credit,debit,pix',
            'payments.*.total_amount' => 'required|numeric|min:0.01',
            'payments.*.installments' => 'required_if:payments.*.method,credit|integer|min:1|max:12',
        ])->validate();

        try {
            DB::beginTransaction();
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'parent_sale_id' => null,
                'total_amount' => $validated['sales'][0]['total_amount'],
                'installment_number' => null,
                'amount' => null,
                'due_date' => null,
                'status' => $validated['sales'][0]['status'],
                'sale_date' => now(),
            ]);

            foreach ($validated['sale_items'] as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            $totalPaid = 0;
            foreach ($validated['payments'] as $payment) {
                $totalPaid += $payment['total_amount'];
                $paymentRecord = Payment::create([
                    'sale_id' => $sale->id,
                    'method' => $payment['method'],
                    'total_amount' => $payment['total_amount'],
                ]);

                if ($payment['method'] === 'credit' && $payment['installments'] > 1) {
                    $installmentAmount = $payment['total_amount'] / $payment['installments'];
                    for ($i = 1; $i <= $payment['installments']; $i++) {
                        InstallmentSale::create([
                            'sale_id' => $sale->id,
                            'installment_number' => $i,
                            'amount' => round($installmentAmount, 2),
                            'due_date' => Carbon::now()->addMonths($i)->startOfDay(),
                            'status' => 'pending',
                        ]);
                    }
                }
            }

            if (abs($totalPaid - $validated['sales'][0]['total_amount']) > 0.01 && $validated['sales'][0]['status'] === 'completed') {
                throw new \Exception('Total paid (' . number_format($totalPaid, 2, ',', '.') . ') must match total amount (' . number_format($validated['sales'][0]['total_amount'], 2, ',', '.') . ') for completed sales.');
            }

            DB::commit();
            return redirect()->route('sales.list')->with('success', 'Venda cadastrada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erro ao cadastrar venda: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $sale = Sale::with(['saleItems', 'payment'])->findOrFail($id);
        $clients = Client::all();
        $products = Product::all();
        return view('sales.edit', compact('sale', 'clients', 'products'));
    }

   public function update(Request $request, $id)
    {
        $data = $request->all();
        if (isset($data['sale_items'])) {
            foreach ($data['sale_items'] as &$item) {
                $item['unit_price'] = str_replace(',', '.', $item['unit_price']);
                $item['subtotal'] = str_replace(',', '.', $item['subtotal']);
            }
        }
        if (isset($data['sales'])) {
            foreach ($data['sales'] as &$sale) {
                $sale['total_amount'] = str_replace(',', '.', $sale['total_amount']);
            }
        }
        if (isset($data['payments'])) {
            foreach ($data['payments'] as &$payment) {
                $amount = isset($payment['total_amount']) ? $payment['total_amount'] : (isset($payment['amount']) ? $payment['amount'] : '0');
                $payment['total_amount'] = str_replace(',', '.', $amount);
                $payment['installments'] = isset($payment['installments']) ? (int) $payment['installments'] : 1;
                unset($payment['amount']);
            }
        }

        $validator = validator($data, [
            'client_id' => 'required|exists:clients,id',
            'sale_items' => 'required|array|min:1',
            'sale_items.*.product_id' => 'required|exists:products,id',
            'sale_items.*.quantity' => 'required|integer|min:1',
            'sale_items.*.unit_price' => 'required|numeric|min:0.01',
            'sale_items.*.subtotal' => 'required|numeric|min:0.01',
            'sales' => 'required|array',
            'sales.*.total_amount' => 'required|numeric|min:0.01',
            'sales.*.status' => 'required|in:pending,completed,canceled',
            'payments' => 'required|array|min:1',
            'payments.*.method' => 'required|in:cash,credit,debit,pix',
            'payments.*.total_amount' => 'required|numeric|min:0.01',
            'payments.*.installments' => 'required_if:payments.*.method,credit|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation failed', $validator->errors()->toArray());
            return back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            DB::beginTransaction();
            $sale = Sale::findOrFail($id);
            $sale->update([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'total_amount' => $validated['sales'][0]['total_amount'],
                'status' => $validated['sales'][0]['status'],
                'sale_date' => now(),
            ]);
            \Log::info('Venda atualizada:', $sale->toArray());

            $sale->saleItems()->delete();
            \Log::info('Itens de venda excluídos para sale_id:', ['sale_id' => $sale->id]);
            foreach ($validated['sale_items'] as $item) {
                $saleItem = SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['subtotal'],
                ]);
                \Log::info('Novo item de venda criado:', $saleItem->toArray());
            }

            $sale->payment()->delete();
            $sale->installmentSales()->delete();
            \Log::info('Pagamentos e parcelas excluídos para sale_id:', ['sale_id' => $sale->id]);
            $totalPaid = 0;
            foreach ($validated['payments'] as $payment) {
                $totalPaid += $payment['total_amount'];
                $paymentRecord = Payment::create([
                    'sale_id' => $sale->id,
                    'method' => $payment['method'],
                    'total_amount' => $payment['total_amount'],
                ]);
                \Log::info('Novo pagamento criado:', $paymentRecord->toArray());

                if ($payment['method'] === 'credit' && $payment['installments'] > 1) {
                    $installmentAmount = $payment['total_amount'] / $payment['installments'];
                    for ($i = 1; $i <= $payment['installments']; $i++) {
                        $installment = InstallmentSale::create([
                            'sale_id' => $sale->id,
                            'installment_number' => $i,
                            'amount' => round($installmentAmount, 2),
                            'due_date' => Carbon::now()->addMonths($i)->startOfDay(),
                            'status' => 'pending',
                        ]);
                        \Log::info('Nova parcela criada:', $installment->toArray());
                    }
                }
            }

            if (abs($totalPaid - $validated['sales'][0]['total_amount']) > 0.01 && $validated['sales'][0]['status'] === 'completed') {
                throw new \Exception('Total paid (' . number_format($totalPaid, 2, ',', '.') . ') must match total amount (' . number_format($validated['sales'][0]['total_amount'], 2, ',', '.') . ') for completed sales.');
            }

            DB::commit();
            return redirect()->route('sales.list')->with('success', 'Venda atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Sale update failed', ['error' => $e->getMessage(), 'data' => $data]);
            return back()->withErrors(['error' => 'Erro ao atualizar venda: ' . $e->getMessage()])->withInput();
        }
    }
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);
        $sale->delete();
        return redirect()->route('sales.list')->with('success', 'Venda excluída com sucesso!');
    }
}