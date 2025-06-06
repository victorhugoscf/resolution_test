@extends('layouts.app')

@section('title', 'Lista de Produtos')

@section('content')
<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-primary">
            <i class="bi bi-box-fill me-2"></i> Lista de Produtos
        </h2>
        <a href="{{ route('products.create') }}" class="btn btn-primary btn-lg px-4 shadow">
            <i class="bi bi-plus-circle me-2"></i> Adicionar Produto
        </a>
    </div>

    <div class="mb-4">
        <div class="input-group shadow-sm rounded-pill">
            <span class="input-group-text bg-light border-0 rounded-start-pill">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-0" placeholder="Buscar por nome ou descrição...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center shadow-sm rounded overflow-hidden" id="productsTable">
            <thead class="table-primary text-uppercase">
                <tr>
                    <th class="py-3">Nome</th>
                    <th class="py-3">Descrição</th>
                    <th class="py-3">Preço</th>
                    <th class="py-3">Estoque</th>
                    <th class="py-3">Ações</th>
                </tr>
            </thead>
            <tbody id="productsBody">
                @forelse ($products as $product)
                    <tr data-id="{{ $product->id }}">
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td class="text-success fw-semibold">R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-danger delete-product" data-id="{{ $product->id }}" title="Remover">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Nenhum produto encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4" id="pagination">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (!csrfToken) {
            console.error('CSRF token não encontrado na meta tag. Verifique layouts.app.');
            alert('Erro de configuração: Token CSRF não encontrado. Recarregue a página.');
        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
        }

        let searchTimeout;
        $('#searchInput').on('keyup', function() {
            clearTimeout(searchTimeout);
            const value = $(this).val().trim();
            
            searchTimeout = setTimeout(function() {
                $.ajax({
                    url: '{{ route("products.list") }}',
                    type: 'GET',
                    data: { search: value },
                    dataType: 'json',
                    beforeSend: function() {
                        $('#productsBody').html('<tr><td colspan="5" class="text-center py-4"><i class="bi bi-hourglass-split text-muted"></i> Carregando...</td></tr>');
                    },
                    success: function(response) {
                        console.log('Resposta de produtos:', response);
                        let html = '';
                        if (response.data && Array.isArray(response.data) && response.data.length > 0) {
                            $.each(response.data, function(index, product) {
                                html += '<tr data-id="' + product.id + '">';
                                html += '<td>' + (product.name || '') + '</td>';
                                html += '<td>' + (product.description || '') + '</td>';
                                html += '<td class="text-success fw-semibold">R$ ' + (product.price_formatted || '0,00') + '</td>';
                                html += '<td>' + (product.stock || 0) + '</td>';
                                html += '<td>';
                                html += '<button class="btn btn-sm btn-outline-danger delete-product" data-id="' + product.id + '" title="Remover">';
                                html += '<i class="bi bi-trash"></i>';
                                html += '</button>';
                                html += '</td>';
                                html += '</tr>';
                            });
                        } else {
                            html = '<tr><td colspan="5" class="text-center text-muted py-4">' +
                                   '<i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>' +
                                   'Nenhum produto encontrado.</td></tr>';
                        }
                        
                        $('#productsBody').fadeOut(150, function() {
                            $(this).html(html).fadeIn(150);
                        });
                        
                        if (response.links) {
                            $('#pagination').fadeOut(150, function() {
                                $(this).html(response.links).fadeIn(150);
                            });
                        } else {
                            $('#pagination').html('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Erro ao buscar produtos - Status:', status);
                        console.log('Erro ao buscar produtos - Error:', error);
                        console.log('Erro ao buscar produtos - Resposta:', xhr.responseText);
                        console.log('Erro ao buscar produtos - HTTP:', xhr.status);
                        $('#productsBody').html('<tr><td colspan="5" class="text-center py-4">' +
                                               '<i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>' +
                                               'Erro ao listar produtos.</td></tr>');
                    }
                });
            }, 200);
        });

       $(document).on('click', '.delete-product', function () {
    const productId = $(this).data('id');

    if (!csrfToken) {
        alert('Erro: Token CSRF não encontrado. Recarregue a página.');
        return;
    }

    if (confirm('Tem certeza de que deseja remover este produto?')) {
        $.ajax({
            url: '{{ route("products.destroy", ":id") }}'.replace(':id', productId),
            type: 'DELETE',
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            beforeSend: function () {
                $('#productsBody').html('<tr><td colspan="5" class="text-center py-4"><i class="bi bi-hourglass-split text-muted"></i> Removendo...</td></tr>');
            },
            success: function (response) {
                console.log('Resposta de exclusão:', response);
                if (response.success) {
                    alert(response.message || 'Produto removido com sucesso!');
                    $('#searchInput').trigger('keyup'); 
                } else {
                    alert(response.message || 'Erro ao remover o produto.');
                    $('#searchInput').trigger('keyup');
                }
            },
            error: function (xhr, status, error) {
                console.log('Erro ao excluir - Status:', status);
                console.log('Erro ao excluir - Error:', error);
                console.log('Erro ao excluir - Resposta:', xhr.responseText);
                console.log('Erro ao excluir - HTTP:', xhr.status);

                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.code === 'PRODUCT_LINKED_TO_SALE') {
                        alert(response.message);
                    } else if (response.message) {
                        alert('Erro: ' + response.message);
                    } else {
                        alert('Erro desconhecido ao remover o produto.');
                    }
                } catch (e) {
                    alert('Erro inesperado ao processar a resposta do servidor.');
                }

                $('#searchInput').trigger('keyup');
            }
        });
    }
});
    });
</script>
@endsection