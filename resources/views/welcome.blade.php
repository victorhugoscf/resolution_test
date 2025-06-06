<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste Prático - DC Tecnologia</title>
     <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #2d3748;
        }
        .container {
            max-width: 1200px;
            padding: 2rem;
            text-align: center;
        }
        .header {
            margin-bottom: 3rem;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 1rem;
        }
        .header p {
            font-size: 1.125rem;
            color: #4a5568;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }
        .logo {
            width: 100px;
            height: auto;
            margin-bottom: 1.5rem;
        }
        .buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            margin-top: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.75rem 2rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #3182ce;
            color: #ffffff;
            border: 2px solid #3182ce;
        }
        .btn-primary:hover {
            background-color: #2b6cb0;
            border-color: #2b6cb0;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background-color: transparent;
            color: #3182ce;
            border: 2px solid #3182ce;
        }
        .btn-secondary:hover {
            background-color: #3182ce;
            color: #ffffff;
            transform: translateY(-2px);
        }
        .footer {
            margin-top: 4rem;
            font-size: 0.875rem;
            color: #718096;
        }
        @media (max-width: 768px) {
            .header h1 {
                font-size: 2rem;
            }
            .header p {
                font-size: 1rem;
            }
            .buttons {
                flex-direction: column;
                gap: 1rem;
            }
            .btn {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Teste Prático para DC Tecnologia</h1>
            <p>Desenvolvido por Victor Hugo, este projeto demonstra um sistema de gestão de vendas, criado como parte do processo seletivo da DC Tecnologia.</p>
        </div>
        <div class="buttons">
            <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
            <a href="{{ route('register') }}" class="btn btn-secondary">Registrar</a>
        </div>
    </div>
    <div class="footer">
        <p>© 2025 Victor Hugo. Todos os direitos reservados.</p>
    </div>
</body>
</html>