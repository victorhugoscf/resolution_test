@extends('layouts.Section')

@section('title', 'Vendas realizadas')

@section('content')
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">
                <i class="bi bi-cart-fill fs-8 me-2"></i> Listagem de Vendas
            </h1>
            <a href="{{ route('sales.create') }}" class="btn btn-primary btn-lg px-4 shadow">
                <i class="bi bi-plus-lg me-2"></i> Nova Venda
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        <div class="mb-4">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" class="form-control border-start-0" id="searchInput" placeholder="Pesquisar por cliente, vendedor ou produto...">
            </div>
        </div>

        <div class="table-responsive shadow-sm rounded">
            <table class="table table-bordered table-hover align-middle text-center" id="salesTable">
                <thead class="table-primary text-uppercase">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Produto</th>
                        <th>Valor Total</th>
                        <th>Pagamento</th>
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
                            <td class="text-success fw-semibold">
                                R$ {{ number_format($sale->total_amount, 2, ',', '.') }}
                            </td>
                            <td>
                                @foreach ($sale->payment as $payment)
                                    <span class="badge bg-light text-dark fw-normal mb-1">
                                        {{ ucfirst($payment->method) }}
                                    </span>
                                @endforeach
                            </td>
                            <td>{{ $sale->created_at->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge bg-secondary">{{ $sale->status }}</span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-warning me-1" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <a href="{{ route('sales.download-pdf', $sale->id) }}" class="btn btn-sm btn-outline-success me-1" title="Baixar PDF">
                                    <i class="bi bi-download"></i>
                                </a>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Tem certeza que deseja excluir esta venda?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Excluir">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#salesTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
@endsection
