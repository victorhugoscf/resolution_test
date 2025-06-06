@extends('layouts.app')

@section('title', 'Lista de Produtos')

@section('content')
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-box-fill fs-8 me-2"></i> Lista de Produtos
        </h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg px-4 shadow">
            <i class="bi bi-plus-circle me-2"></i> Adicionar Produto
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center shadow-sm rounded overflow-hidden">
            <thead class="table-primary text-uppercase">
                <tr>
                    <th class="py-3">Nome</th>
                    <th class="py-3">Descrição</th>
                    <th class="py-3">Preço</th>
                    <th class="py-3">Estoque</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td class="text-success fw-semibold">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection
