<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@extends('layouts.app')

@section('title', 'Vendas')

@section('content')

<div class="container mt-5">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
    @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="fw-bold text-primary">📋 Lista de Clientes</h1>
            <a href="{{  route('clients.create') }}" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Novo Cliente
            </a>
        </div>
    

    <form id="clientSearchForm" method="GET" action="{{ route('clients.index') }}" class="mb-4">
        <div class="input-group shadow-sm rounded-pill">
            <span class="input-group-text bg-light">
                <i class="bi bi-search text-muted"></i>
            </span>
            <input type="text" name="search" class="form-control border-0" placeholder="Buscar por nome, email ou documento..." value="{{ request('search') }}">
            <button class="btn btn-primary rounded-pill" type="submit">
                Buscar
            </button>
        </div>
    </form>

    <div class="table-responsive shadow-sm rounded">
        <table class="table table-hover align-middle text-center">
            <thead class="table-primary">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Documento</th>
                    <th>Endereço</th>
                </tr>
            </thead>
            <tbody>
                @include('clients._table', ['clients' => $clients])
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $clients->links('pagination::bootstrap-5') }}
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function () {
        $('#clientSearchForm').on('submit', function (e) {
            e.preventDefault(); 
            let url = $(this).attr('action');
            let query = $(this).serialize();
            $.ajax({
                url: url + '?' + query,
                type: 'GET',
                success: function (data) {
                    $('tbody').fadeOut(150, function () {
                     $(this).html(data).fadeIn(150);
                });

                },
                error: function () {
                    alert('Erro ao buscar os clientes.');
                }
            });
        });
    });
</script>
@endsection