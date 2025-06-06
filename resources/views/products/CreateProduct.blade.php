@extends('layouts.app')

@section('title', 'Cadastro de Produto')

@section('content')
<body style="background-color: #f8f9fa;">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-square-fill"></i> Cadastro de Produto
            </h2>
            <a href="{{ route('products.list') }}" class="btn btn-secondary shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ route('products.store') }}" class="shadow p-4 rounded bg-white needs-validation" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label fw-semibold text-dark">Nome <span class="text-danger">*</span></label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Digite o nome do produto" 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Campo obrigatório.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label fw-semibold text-dark">Descrição</label>
                <textarea name="description" 
                          id="description" 
                          class="form-control form-control-lg @error('description') is-invalid @enderror" 
                          rows="3" 
                          placeholder="Digite a descrição do produto"></textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="price" class="form-label fw-semibold text-dark">Preço <span class="text-danger">*</span></label>
                <input type="number" 
                       name="price" 
                       id="price" 
                       class="form-control form-control-lg @error('price') is-invalid @enderror" 
                       value="{{ old('price') }}" 
                       step="0.01" 
                       required 
                       placeholder="Digite o preço">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="stock" class="form-label fw-semibold text-dark">Estoque <span class="text-danger">*</span></label>
                <input type="number" 
                       name="stock" 
                       id="stock" 
                       class="form-control form-control-lg @error('stock') is-invalid @enderror" 
                       value="{{ old('stock') }}" 
                       min="0" 
                       required 
                       placeholder="Quantidade disponível">
                @error('stock')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-save me-2"></i> Cadastrar Produto
                </button>
            </div>
        </form>
    </div>
</body>

@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>       
    $(document).ready(function () {
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    });
</script>
</html>
