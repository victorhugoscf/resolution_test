@extends('layouts.app')

@section('title', 'Vendas')

@section('content')

<div class="container mt-5">
    {{-- Alerta de sucesso --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    {{-- Cabeçalho com título e botão --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold text-primary">
             <i class="bi bi-people-fill fs-8 me-2"></i>Lista de Clientes
        </h1>
        <a href="{{ route('clients.create') }}" class="btn btn-primary btn-lg px-4 shadow">
            <i class="bi bi-plus-lg me-1"></i> Novo Cliente
        </a>
    </div>

    {{-- Campo de busca --}}
    <div class="mb-4">
        <div class="input-group shadow-sm rounded-pill">
            <span class="input-group-text bg-light border-0 rounded-start-pill">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" id="searchInput" class="form-control border-0" placeholder="Buscar por nome, email ou documento..." value="{{ request('search') }}">
        </div>
    </div>

    {{-- Tabela de clientes --}}
    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle text-center mb-0" id="clientsTable">
            <thead class="table-primary">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Documento</th>
                    <th>Endereço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @include('clients._table', ['clients' => $clients])
            </tbody>
        </table>
    </div>

    {{-- Paginação --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var value = $(this).val();
            $.ajax({
                url: '{{ route("clients.index") }}',
                type: 'GET',
                data: { search: value },
                success: function(data) {
                    $('tbody').fadeOut(150, function() {
                        $(this).html(data).fadeIn(150);
                    });
                },
                error: function() {
                    alert('Erro ao buscar os clientes.');
                }
            });
        });
    });
</script>
@endsection