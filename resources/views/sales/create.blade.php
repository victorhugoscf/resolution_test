@extends('layouts.Section')

@section('title', 'Nova Venda')

@section('content')
<div class="container mt-5">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2 fs-4"></i>
        <div class="flex-grow-1">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    @endif

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="fw-bold text-primary">
        <i class="bi bi-cart-plus-fill fs-8 me-2"></i> Nova Venda
    </h1>
   <a href="{{ route('sales.list') }}" class="btn btn-secondary shadow-sm d-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i> Voltar
        </a>
</div>


    <form action="{{ route('sales.list') }}" method="POST" class="needs-validation" novalidate>
        @csrf

        {{-- Cliente --}}
        <div class="mb-4">
            <label for="client_id" class="form-label fw-semibold">Cliente</label>
            <select name="client_id" id="client_id" class="form-select select2 @error('client_id') is-invalid @enderror" required>
                <option value="" disabled selected>Selecione um cliente</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                        {{ $client->name }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Produtos --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Produtos</label>
            <div class="table-responsive shadow-sm rounded border">
                <table class="table table-bordered align-middle text-center mb-0">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Produto</th>
                            <th style="width:100px;">Qtd</th>
                            <th style="width:130px;">Preço Unit.</th>
                            <th style="width:130px;">Subtotal</th>
                            <th style="width:110px;">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="sale-item-row">
                            <td>
                                <select name="sale_items[0][product_id]" class="form-select product-select select2" required>
                                    <option value="" disabled selected>Selecione um produto</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="sale_items[0][quantity]" class="form-control quantity" value="1" min="1" required></td>
                            <td><input type="text" name="sale_items[0][unit_price]" class="form-control unit-price" value="0.00" readonly></td>
                            <td><input type="text" name="sale_items[0][subtotal]" class="form-control subtotal" value="0.00" readonly></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-item shadow-sm">
                                    <i class="bi bi-trash"></i> Remover
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Total --}}
        <div class="mb-4">
            <label for="total_amount" class="form-label fw-semibold">Valor Total</label>
            <input type="text" name="sales[0][total_amount]" id="total_amount" class="form-control form-control-lg fw-bold text-primary" value="0.00" readonly>
        </div>

        {{-- Status --}}
        <div class="mb-4">
            <label for="status" class="form-label fw-semibold">Status</label>
            <select name="sales[0][status]" id="status" class="form-select @error('sales.0.status') is-invalid @enderror" required>
                <option value="pending" selected>Pendente</option>
                <option value="completed">Concluído</option>
                <option value="canceled">Cancelado</option>
            </select>
            @error('sales.0.status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Pagamento --}}
        <div class="mb-4">
            <label class="form-label fw-semibold">Formas de Pagamento</label>
            <div id="payments-container">
                <div class="payment-row mb-3 p-3 border rounded bg-light shadow-sm">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <select name="payments[0][method]" class="form-select payment-method" required>
                                <option value="" disabled selected>Selecione</option>
                                <option value="cash">Dinheiro</option>
                                <option value="credit">Cartão de Crédito</option>
                                <option value="debit">Cartão de Débito</option>
                                <option value="pix">PIX</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="payments[0][total_amount]" class="form-control payment-amount" value="0.00" required>
                        </div>
                        <div class="col-md-2 installment-container" style="display: none;">
                            <select name="payments[0][installments]" class="form-select installment-select">
                                <option value="1" selected>1x (À vista)</option>
                                @for ($i = 2; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}x</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end align-items-center gap-2">
                            <span class="installment-value text-muted" style="display: none;">Parcela: R$ 0,00</span>
                            <button type="button" class="btn btn-outline-danger btn-sm remove-payment shadow-sm">
                                <i class="bi bi-trash"></i> Remover
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button" class="btn btn-secondary btn-sm shadow-sm d-flex align-items-center gap-1" id="add-payment">
                <i class="bi bi-plus-circle fs-5"></i> Adicionar Forma de Pagamento
            </button>

            <div class="mt-3">
                <p class="mb-1 fw-semibold">Total Pago: <span id="total-paid" class="text-success">R$ 0.00</span></p>
                <p class="mb-0 fw-semibold">Restante a Pagar: <span id="remaining-amount" class="text-danger">R$ 0.00</span></p>
            </div>
            @error('payments')
                <div class="text-danger mt-2">{{ $message }}</div>
            @enderror
        </div>

        {{-- Botões --}}
        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">Salvar</button>
            <a href="{{ route('sales.list') }}" class="btn btn-outline-secondary px-4 fw-semibold">Cancelar</a>
        </div>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function () {
    let itemIndex = 1;    
    let paymentIndex = 1; 

    function formatCurrencyBR(value) {
        return value.toFixed(2).replace('.', ',');
    }

    function parseCurrencyBR(value) {
        return parseFloat(value.toString().replace(',', '.')) || 0;
    }

    function calculateTotal() {
        let total = 0;
        $('.sale-item-row').each(function () {
            const quantity = parseFloat($(this).find('.quantity').val()) || 0;
            const unitPrice = parseCurrencyBR($(this).find('.unit-price').val());
            const subtotal = quantity * unitPrice;
            $(this).find('.subtotal').val(formatCurrencyBR(subtotal));
            total += subtotal;
        });
        $('#total_amount').val(formatCurrencyBR(total));
        calculateRemaining();
    }

    function calculateRemaining() {
        let total = parseCurrencyBR($('#total_amount').val());
        let totalPaid = 0;
        $('.payment-row').each(function () {
            let amount = parseCurrencyBR($(this).find('.payment-amount').val());
            totalPaid += amount;
            let method = $(this).find('.payment-method').val();
            let installments = parseInt($(this).find('.installment-select').val()) || 1;
            if (method === 'credit' && installments > 0) {
                let installmentValue = amount / installments;
                $(this).find('.installment-value').text('Parcela: R$ ' + formatCurrencyBR(installmentValue));
            }
        });
        $('#total-paid').text('R$ ' + formatCurrencyBR(totalPaid));
        $('#remaining-amount').text('R$ ' + formatCurrencyBR(total - totalPaid));
    }

    $(document).on('change', '.product-select', function () {
        const selectedOption = $(this).find(':selected');
        const price = parseFloat(selectedOption.data('price')) || 0;
        const row = $(this).closest('.sale-item-row');
        row.find('.unit-price').val(formatCurrencyBR(price));
        calculateTotal();
    });

    $(document).on('input', '.quantity', calculateTotal);
    $('#add-item').on('click', function () {
        const row = `
            <tr class="sale-item-row">
                <td>
                    <select name="sale_items[${itemIndex}][product_id]" class="form-select product-select select2" required>
                        <option value="" disabled selected>Selecione um produto</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="sale_items[${itemIndex}][quantity]" class="form-control quantity" value="1" min="1" required>
                </td>
                <td>
                    <input type="text" name="sale_items[${itemIndex}][unit_price]" class="form-control unit-price" value="0.00" readonly>
                </td>
                <td>
                    <input type="text" name="sale_items[${itemIndex}][subtotal]" class="form-control subtotal" value="0.00" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-item" title="Remover produto">
                        <i class="bi bi-trash"></i> Remover
                    </button>
                </td>
            </tr>
        `;
        $('#sale-items-table tbody').append(row);
        if (jQuery().select2) {
            $('#sale-items-table tbody .select2:last').select2();
        }
        itemIndex++;
        calculateTotal();
    });

    $(document).on('click', '.remove-item', function () {
        if ($('.sale-item-row').length > 1) {
            $(this).closest('.sale-item-row').remove();
            calculateTotal();
        }
    });

    $('#add-payment').on('click', function () {
        const paymentRow = `
            <div class="payment-row mb-3 p-3 border rounded bg-light">
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <select name="payments[${paymentIndex}][method]" class="form-select payment-method" required>
                            <option value="" disabled selected>Selecione</option>
                            <option value="cash">Dinheiro</option>
                            <option value="credit">Cartão de Crédito</option>
                            <option value="debit">Cartão de Débito</option>
                            <option value="pix">PIX</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="payments[${paymentIndex}][total_amount]" class="form-control payment-amount" value="0.00" required>
                    </div>
                    <div class="col-md-2 installment-container" style="display: none;">
                        <select name="payments[${paymentIndex}][installments]" class="form-select installment-select">
                            <option value="1" selected>1x (À vista)</option>
                            @for ($i = 2; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $i }}x</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 d-flex justify-content-end align-items-center">
                        <span class="installment-value text-muted me-2" style="display: none;">Parcela: R$ 0,00</span>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-payment" title="Remover forma de pagamento">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#payments-container').append(paymentRow);
        paymentIndex++;
        calculateRemaining();
    });

    $(document).on('click', '.remove-payment', function () {
        if ($('.payment-row').length > 1) {
            $(this).closest('.payment-row').remove();
            calculateRemaining();
        }
    });

    $(document).on('change', '.payment-method', function () {
        const row = $(this).closest('.payment-row');
        const method = $(this).val();
        const installmentContainer = row.find('.installment-container');
        const installmentValue = row.find('.installment-value');
        if (method === 'credit') {
            installmentContainer.show();
            installmentValue.show();
            calculateRemaining();
        } else {
            installmentContainer.hide();
            installmentValue.hide();
        }
    });

    $(document).on('change input', '.installment-select, .payment-amount', function () {
        calculateRemaining();
    });
    $(document).on('input', '.payment-amount', calculateRemaining);

    calculateTotal();
    calculateRemaining();

    if (jQuery().select2) {
        $('.select2').select2();
    }
});
</script>
@endsection