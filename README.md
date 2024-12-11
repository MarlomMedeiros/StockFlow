# StockFlow

O projeto StockFlow é um sistema completo para gerenciamento de estoque, focado em facilitar o controle de produtos, fornecedores, categorias e movimentações de estoque. Com diferentes níveis de acesso, é ideal para empresas que buscam eficiência na gestão de seus recursos.

## Funcionalidades Principais

- Cadastro, edição e exclusão de produtos, fornecedores e categorias.
- Controle de movimentações de estoque (entradas e saídas).
- Listagens de produtos com estoque baixo ou vencimento próximo.
- Permissões baseadas em papéis de usuário (administrador, gerente, usuário comum).
- O primeiro usuário a se registrar será o administrador.

## Instruções de Execução

Siga estas instruções para configurar o projeto StockFlow em sua máquina local para desenvolvimento e teste.

### Pré-requisitos

Certifique-se de que sua máquina atenda aos seguintes requisitos:

- PHP >= 8.1
- Composer - [Instalação do Composer](https://getcomposer.org/download/)
- Node.js e NPM - [Instalação do Node.js](https://nodejs.org/)
- MySQL - [Instalação do MySQL](https://dev.mysql.com/downloads/mysql/8.0.html)

### Instalação

Siga os passos abaixo para configurar o projeto:

1. Clone o repositório:

```bash
git clone https://github.com/SeuUsuario/stockflow.git
cd stockflow
```

2. Instale as dependências do PHP executando:

```bash
composer install
```

3. Instale as dependências do front-end executando:

```bash
npm install
```

4. Faça uma cópia do arquivo `.env.example` para `.env`:

```bash
cp .env.example .env
```

5. Gere uma chave de aplicativo:

```bash
php artisan key:generate
```

6. Configure o banco de dados no arquivo `.env`. Exemplo:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco_de_dados
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

7. Execute as migrações para criar as tabelas no banco de dados:

```bash
php artisan migrate
```

8. Inicie o servidor de desenvolvimento:

```bash
php artisan serve
```

9. Compile com o npm
   
```bash
npm run build
```

10. Acesse o projeto em seu navegador através da URL:

```
http://localhost:8000
```
