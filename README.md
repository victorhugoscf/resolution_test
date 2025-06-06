📊 Projeto de Gestão de Vendas
Aplicação em Laravel para gerenciamento de vendas, clientes, produtos, pagamentos e parcelas, com geração de PDF contendo os detalhes das vendas.

✅ Pré-requisitos
PHP 8.1 ou superior

Composer (Gerenciador de dependências PHP)

Laravel 10.x

MySQL ou outro banco suportado

Node.js & NPM (para assets frontend / JS)

Git (para controle de versão)

🚀 Instalação
1. Clonar o repositório
'''bash
git clone https://github.com/victorhugoscf/resolution_test
cd resolution_test
'''

2. Instalar dependências PHP
'''bash
composer install
'''

3. Configurar o ambiente
Copie o arquivo .env de exemplo:

'''bash
cp .env.example .env
'''

Edite as variáveis de ambiente no arquivo .env:

'''env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha
'''

4. Gerar chave da aplicação
'''bash
php artisan key:generate
'''

5. Migrar e popular o banco
'''bash
php artisan migrate
php artisan db:seed
'''

6. Instalar dependência de PDF
'''bash
composer require barryvdh/laravel-dompdf
'''

7. Iniciar o servidor
'''bash
php artisan serve
'''

Acesse: http://localhost:8000

⚙️ Funcionalidades
📋 Listagem de Vendas: veja todas as vendas com detalhes do cliente, produto e pagamento

➕ Criar Venda: cadastre novas vendas com produtos e métodos de pagamento

📄 Download em PDF: gere um PDF com dados da venda, cliente, produtos e parcelas

🔍 Pesquisa: filtre vendas por cliente, vendedor ou produto

🧪 Uso
Acessar a aplicação
Abra o navegador em: http://localhost:8000

Criar uma nova venda
Clique em "Nova Venda"

Preencha os dados do cliente, produtos e pagamento

Baixar PDF
Na listagem de vendas, clique no ícone de download

Um PDF será gerado com todas as informações detalhadas

