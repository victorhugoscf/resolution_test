<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	protected $productService;

	public function __construct(ProductService $productService)
	{
		$this->productService = $productService;
	}

	public function index(Request $request)
	{
		$products = $this->productService->getProducts($request);

		if ($request->ajax()) {
			return response()->json([
				'data'  => $products->items(),
				'links' => (string) $products->links('pagination::bootstrap-5'),
			], 200);
		}

		return view('products.ProductList', compact('products'));
	}

	public function create()
	{
		$product = $this->productService->createProduct();
		return view('products.CreateProduct', compact('product'));
	}

	public function store(StoreProductRequest $request)
	{
		$this->productService->storeProduct($request->validated());
		return redirect()
			->route('products.list')
			->with('success', 'Produto cadastrado com sucesso!');
	}

	public function edit($id)
	{
		$product = $this->productService->findProduct((int) $id);
		return view('products.form', compact('product'));
	}

	public function update(UpdateProductRequest $request, $id)
	{
		$data = $request->validated();

		$this->productService->updateProduct(
			(int) $id,
			$data
		);

		return redirect()
			->route('products.list')
			->with('success', 'Produto atualizado com sucesso!');
	}

	public function destroy($id, Request $request)
	{
		try {
			$this->productService->deleteProduct((int) $id);

			if ($request->ajax()) 
			{
				return response()->json([
					'success' => true,
					'message' => 'Produto excluído com sucesso!'
				], 200);
			}

			return redirect()
				->route('products.list')
				->with('success', 'Produto excluído com sucesso!');

		} catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) 
		{
			if ($request->ajax()) 
			{
				return response()->json([
					'success' => false,
					'message' => 'Produto não encontrado.'
				], 404);
			}
			return redirect()
				->route('products.list')
				->with('error', 'Produto não encontrado.');

		} catch (Throwable $e) 
		{
			Log::error('Erro ao excluir produto: ' . $e->getMessage(), [
				'produto_id' => $id,
				'trace'      => $e->getTraceAsString()
			]);

			$mensagem = $e->getMessage() === 'Não é possível excluir o produto, pois ele está vinculado a uma ou mais vendas.'
					? $e->getMessage()
					: 'Erro ao excluir o produto.';

			if ($request->ajax()) 
			{
				return response()->json([
					'success' => false,
					'message' => $mensagem
				], 400);
			}

			return redirect()
				->route('products.list')
				->with('error', $mensagem);
		}
	}
}
