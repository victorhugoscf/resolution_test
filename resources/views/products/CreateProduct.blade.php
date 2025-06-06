
@extends('layouts.app')

@section('title', 'Cadastro de Produto')

@section('content')
<div class="container mt-5">
<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
    <h2 class="fw-bold text-primary d-flex align-items-center gap-2">
        <i class="bi-plus-square-fill fs-8 me-2"></i> Cadastro de Produto
    </h2>
    <a href="{{ route('products.list') }}" class="btn btn-secondary shadow-sm d-flex align-items-center gap-1">
        <i class="bi bi-arrow-left fs-5"></i> <span class="fw-semibold">Voltar</span>
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
      <button type="submit" class="btn btn-primary btn-lg">
        <i class="bi bi-check-circle-fill me-1"></i> Cadastrar Produto
      </button>
    </div>
  </form>
</div>

@endsection
