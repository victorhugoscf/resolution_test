<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@extends('layouts.app')

@section('title', 'Cadastro de Produto')

@section('content')
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-success">📝 Cadastro de Produto</h2>
        <a href="{{ route('products.list') }}" class="btn btn-secondary btn-lg shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>

    <form method="POST" action="{{ route('products.store') }}" class="shadow-sm p-4 rounded bg-light">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-bold">Nome *</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label fw-bold">Descrição</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label fw-bold">Preço *</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label fw-bold">Estoque *</label>
            <input type="number" name="stock" id="stock" class="form-control" min="0" required>
        </div>
        
        <div class="d-grid">
            <button type="submit" class="btn btn-success btn-lg">
                <i class="bi bi-check-circle-fill me-1"></i> Cadastrar Produto
            </button>
        </div>
    </form>
</div>
@endsection
