@extends('layouts.section')

@section('title', 'Cadastro de clientes')

@section('content')
<body style="background-color: #f8f9fa;">
    <div class="container mt-5" style="max-width: 600px;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary d-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i> Cadastro de Cliente
            </h2>
            <a href="{{ route('clients.index') }}" class="btn btn-secondary shadow-sm d-flex align-items-center gap-1">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form method="POST" action="{{ route('clients.store') }}" class="shadow p-4 rounded bg-white needs-validation" novalidate>
            @csrf

            <div class="mb-4">
                <label for="name" class="form-label fw-semibold text-dark">Nome <span class="text-danger">*</span></label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control form-control-lg @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       required 
                       placeholder="Digite o nome do cliente" 
                       autofocus>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Campo obrigatório.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="email" class="form-label fw-semibold text-dark">Email</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       class="form-control form-control-lg @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="exemplo@dominio.com">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Deve ser um email válido.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label fw-semibold text-dark">Telefone</label>
                <input type="tel" 
                       name="phone" 
                       id="phone" 
                       class="form-control form-control-lg @error('phone') is-invalid @enderror" 
                       value="{{ old('phone') }}" 
                       placeholder="88 9 9807 2532">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Deve ser um número válido.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="address" class="form-label fw-semibold text-dark">Endereço</label>
                <input type="text" 
                       name="address" 
                       id="address" 
                       class="form-control form-control-lg @error('address') is-invalid @enderror" 
                       value="{{ old('address') }}" 
                       placeholder="Rua x, número y, bairro z.">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Deve ser um endereço válido.</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="document" class="form-label fw-semibold text-dark">CPF</label>
                <input type="text" 
                       name="document" 
                       id="document" 
                       class="form-control form-control-lg @error('document') is-invalid @enderror" 
                       value="{{ old('document') }}" 
                       placeholder="000.000.000-00" 
                       maxlength="14">
                @error('document')
                    <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Utilize o formato: 000.000.000-00</div>
                @enderror
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                    <i class="bi bi-save me-2"></i> Cadastrar
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