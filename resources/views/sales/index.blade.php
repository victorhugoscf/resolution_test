@extends('layouts.app')

@section('title', 'Vendas realizadas')

@section('content')
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">📊 Listagem de Vendas</h1>
            <a href="{{ route('sales.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Nova Venda
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif 
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Produto</th>
                        <th>Valor Total</th>
                        <th>Forma de Pagamento</th>
                        <th>Data</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $sale->client->name }}</td>
                            <td>{{ $sale->user->name }}</td>
                            <td>
                     @foreach ($sale->products as $product)
                            <span class="d-block">{{ $product->name }}</span>
                     @endforeach
                            </td>
                            <td>R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</td>
                           <td>
                            @foreach ($sale->payment as $payment)
                                 <span class="d-block">{{ ucfirst($payment->method) }}</span>
                            @endforeach
                                </td>
                            <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                            <td>{{ $sale->status }}</td>
                            <td class="text-center">
                                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{}" class="btn btn-sm btn-outline-success me-1">
                                     <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                                    
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $sales->links('pagination::bootstrap-5') }}
    </div>
        </div>
    </div>
</body>
@endsection
