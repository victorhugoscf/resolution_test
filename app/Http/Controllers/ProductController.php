<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $query = Product::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $products = $query->latest()->paginate(10);

        $products->getCollection()->transform(function ($product) {
            $product->price_formatted = number_format($product->price, 2, ',', '.');
            return $product;
        });

        if ($request->ajax()) {
            return response()->json([
                'data' => $products->items(),
                'links' => (string) $products->links('pagination::bootstrap-5')
            ], 200);
        }

        return view('products.ProductList', compact('products'));
    }

    public function create()
    {
        return view('products.CreateProduct', ['product' => new Product()]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.list')->with('success', 'Produto cadastrado com sucesso!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.form', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0.01',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.list')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id, Request $request)
{
    try {
        $product = Product::findOrFail($id);
        if ($product->saleItems()->exists()) {
            $message = 'Não é possível excluir o produto, pois ele está vinculado a uma ou mais vendas.';
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 400);
            }

            return redirect()->route('products.list')->with('error', $message);
        }

        $product->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produto excluído com sucesso!'
            ], 200);
        }

        return redirect()->route('products.list')->with('success', 'Produto excluído com sucesso!');
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.'
            ], 404);
        }
        return redirect()->route('products.list')->with('error', 'Produto não encontrado.');
    } catch (\Exception $e) {
        \Log::error('Erro ao excluir produto: ' . $e->getMessage(), ['id' => $id, 'trace' => $e->getTraceAsString()]);
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao excluir o produto: ' . $e->getMessage()
            ], 500);
        }
        return redirect()->route('products.list')->with('error', 'Erro ao excluir o produto.');
    }
}

}