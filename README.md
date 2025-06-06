Projeto de Gestão de Vendas
Uma aplicação em Laravel para gerenciar vendas, clientes, produtos, pagamentos e parcelas, com geração de PDF para detalhes das vendas.
Pré-requisitos

PHP: 8.1 ou superior
Composer: Gerenciador de dependências para PHP
Laravel: 10.x ou versão compatível
MySQL: Ou outro banco de dados suportado pelo Laravel
Node.js & NPM: Para assets de frontend / Javascript/Jquery
Git: Para controle de versão

Instalação

Clonar o Repositório
git clone <https://github.com/victorhugoscf/resolution_test>
cd resolution_test


Instalar Dependências

Instale as dependências PHP:composer install

Configurar o Ambiente

Copie o arquivo de exemplo .env:cp .env.example .env


Edite o arquivo .env para configurar o banco de dados e outras opções:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha


Gerar a Chave da Aplicação
php artisan key:generate


Executar as Migrações

Crie e popule as tabelas do banco de dados: php artisan migrate
Popule o banco de dados com ajuda dos seeders: php artisan db:seed


Instalar Dependência de PDF

Instale o pacote barryvdh/laravel-dompdf para geração de PDF:composer require barryvdh/laravel-dompdf


Iniciar o Servidor

Inicie o servidor de desenvolvimento do Laravel: php artisan serve


Acesse a aplicação em: http://localhost:8000


Funcionalidades

Listagem de Vendas: Visualize todas as vendas com detalhes de cliente, produto e pagamento.
Criar Venda: Adicione novas vendas com produtos e métodos de pagamento.
Download de PDF: Gere e baixe um PDF com detalhes da venda, incluindo informações do cliente, produtos, pagamentos e parcelas (para pagamentos com cartão de crédito).
Pesquisa: Filtre vendas por cliente, vendedor ou produto.

Uso

Acessar a Aplicação

Abra o navegador e vá para http://localhost:8000.
Navegue até a página "Listagem de Vendas" para ver as vendas existentes.


Criar uma Venda

Clique em "Nova Venda" para adicionar uma nova venda.
Preencha os detalhes do cliente, produtos e pagamento.


Baixar PDF

Na tabela de vendas, clique no botão de download para uma venda.
Um arquivo PDF será baixado, mostrando detalhes do cliente, produtos, métodos de pagamento e parcelas.



