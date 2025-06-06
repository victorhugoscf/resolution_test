<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductService
{
	/**
	 * Retorna uma listagem paginada de produtos, aplicando filtro de busca se fornecido.
	 *
	 * @param  Request  $request
	 * @return LengthAwarePaginator
	 */
	public function getProducts(Request $request): LengthAwarePaginator
	{
		$search = $request->query('search');
		$query  = Product::query();

		if (!empty($search)) {
			$query->where(function ($q) use ($search) {
				  $q->where('name', 'like', "%{$search}%")
				  	->orWhere('description', 'like', "%{$search}%");
			});
		}

		$paginator = $query->latest()->paginate(10);

		$paginator->getCollection()->transform(function (Product $product) 
		{
			$product->price_formatted = number_format($product->price, 2, ',', '.');
			return $product;
		});

		return $paginator;
	}

	/**
	 * Retorna uma nova instância vazia de Product, para ser usada no formulário de criação.
	 *
	 * @return Product
	 */
	public function createProduct(): Product
	{
			return new Product();
	}

	/**
	 * Armazena um novo produto no banco.
	 * 
	 * @param  array  $data  Dados já validados (name, description, price, stock)
	 * @return Product
	 */
	public function storeProduct(array $data): Product
	{
		return Product::create($data);
	}

	/**
	 * Busca um produto pelo ID ou lança ModelNotFoundException.
	 *
	 * @param  int  $id
	 * @return Product
	 *
	 * @throws ModelNotFoundException
	 */
	public function findProduct(int $id): Product
	{
		return Product::findOrFail($id);
	}

	/**
	 * Atualiza os dados de um produto existente.
	 *
	 * @param  int    $id
	 * @param  array  $data  Dados já validados (name, description, price, stock)
	 * @return Product
	 *
	 * @throws ModelNotFoundException
	 */
	public function updateProduct(int $id, array $data): Product
	{
		$product = $this->findProduct($id);
		$product->update($data);
		return $product;
	}

	/**
	 * Exclui um produto. Antes de deletar, verifica se há vinculo com saleItems().
	 *
	 * @param  int  $id
	 * @return void
	 *
	 * @throws \Exception  Se o produto estiver vinculado a itens de venda.
	 * @throws ModelNotFoundException
	 */
	public function deleteProduct(int $id): void
	{
		$product = $this->findProduct($id);

		if ($product->saleItems()->exists()) 
		{
			throw new \Exception('Não é possível excluir o produto, pois ele está vinculado a uma ou mais vendas.');
		}

		$product->delete();
	}
}
