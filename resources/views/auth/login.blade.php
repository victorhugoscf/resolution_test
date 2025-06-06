@extends('layouts.Section')

@section('title', 'Login')

@section('content')
<div class="d-flex align-items-center justify-content-center bg-light" style="min-height: 100vh;">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h2 class="text-center mb-4 text-primary">Acesso ao Sistema</h2>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>

                <div class="text-center">
                    <p>
                        <a href="{{ route('register') }}" class="text-decoration-none">Criar uma conta</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
