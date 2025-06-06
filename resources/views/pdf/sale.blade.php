<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes da Venda #{{ $sale->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 20px;
        }
        h1 {
            color: #007bff;
            text-align: center;
        }
        h2 {
            color: #333;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        .total {
            font-weight: bold;
            color: #28a745;
        }
    </style>
</head>
<body>
    <h1>Detalhes da Venda #{{ $sale->id }}</h1>

    <h2>Dados do Cliente</h2>
    <table>
        <tr>
            <th>Nome</th>
            <td>{{ $sale->client->name }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $sale->client->email ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Telefone</th>
            <td>{{ $sale->client->phone ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Documento</th>
            <td>{{ $sale->client->document ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Endereço</th>
            <td>{{ $sale->client->address ?? 'Não informado' }}</td>
        </tr>
    </table>

    <h2>Produtos</h2>
    <table>
        <thead>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sale->products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->pivot->quantity }}</td>
                    <td>R$ {{ number_format($product->pivot->unit_price, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($product->pivot->subtotal, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Métodos de Pagamento</h2>
    <table>

        <tbody>
            @foreach ($sale->payment as $payment)
                <tr>
                    <td>{{ ucfirst($payment->method) }}</td>
                    <td>R$ {{ number_format($payment->total_amount, 2, ',', '.') }}</td>
                </tr>
                @if (str_contains(strtolower($payment->method), 'credit') && $sale->installmentSales->isNotEmpty())
                    <tr>
                        <td colspan="2">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nº Parcela</th>
                                        <th>Valor</th>@ 
                                        <th>Vencimento</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sale->installmentSales as $installment)
                                        <tr>
                                            <td>{{ $installment->installment_number }}</td>
                                            <td>R$ {{ number_format($installment->amount, 2, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}</td>
                                            <td>{{ ucfirst($installment->status) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <p class="total">Valor Total: R$ {{ number_format($sale->total_amount, 2, ',', '.') }}</p>
    <p>Data da Venda: {{ $sale->created_at->format('d/m/Y') }}</p>
    <p>Status: {{ $sale->status }}</p>
</body>
</html>