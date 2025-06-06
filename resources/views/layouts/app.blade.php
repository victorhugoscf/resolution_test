<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background: linear-gradient(90deg, #0d6efd, #0b5ed7);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .navbar-custom .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: background 0.3s, padding-left 0.3s;
        }
        .navbar-custom .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            padding-left: 12px;
        }
        footer {
            background: #212529;
            color: #adb5bd;
            position: relative;
            bottom: 0;
            width: 100%;
            z-index: 1000;
        }
        footer p {
            margin: 0;
            padding: 1rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold text-white" href="#">Sistema</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-person-circle me-1"></i> Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.list') }}">
                                <i class="bi bi-cart-fill fs-5 me-2"></i> Vendas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('sales.create') }}">
                                <i class="bi bi-cart-plus-fill fs-5 me-2"></i> Nova Venda
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.index') }}">
                                <i class="bi bi-people-fill fs-5 me-2"></i> Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.create') }}">
                                <i class="bi bi-person-plus-fill fs-5 me-2"></i> Novo Cliente
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.list') }}">
                                <i class="bi bi-box-fill fs-5 me-2"></i> Produtos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.create') }}">
                                <i class="bi-plus-square-fill fs-5 me-2"></i> Criar Produto
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light ms-lg-3">
                                    <i class="bi bi-box-arrow-right me-1"></i> Sair
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        @yield('content')
    </div>

    <footer class="text-center mt-5">
        <p>&copy; {{ date('Y') }} Sistema - Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
