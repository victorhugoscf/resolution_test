@extends('layouts.app')

@section('title', 'Cadastro de clientes')

@section('content')
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary">📝 Cadastro de Cliente</h2>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-lg shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ route('clients.store') }}" class="shadow-sm p-4 rounded bg-light needs-validation" novalidate>
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label fw-bold">Nome <span class="text-danger">*</span></label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Digite o nome do cliente">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Campo obrigatório.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Email</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="exemplo@dominio.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Opcional. Se informado, deve ser um email válido.</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="document" class="form-label fw-bold">CPF</label>
                <input type="text" 
                       name="document" 
                       id="document" 
                       class="form-control @error('document') is-invalid @enderror" 
                       value="{{ old('document') }}" 
                       placeholder="000.000.000-00">
                @error('document')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Opcional. Utilize o formato: 000.000.000-00</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i> Cadastrar
            </button>
        </form>
    </div>
   @endsection
   
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
      $(document).ready(function () {
        $('#document').mask('000.000.000-00');

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
</body>
</html>

