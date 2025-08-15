# ProjetoTeste

README bilíngue (Português / English) — documentação completa do projeto.

-- Sumário / Table of Contents

- About / Sobre
- Requirements / Requisitos
- Installation / Instalação
- Database / Banco de dados
- Assets / Assets (Vite)
- Running the app / Executando a aplicação
- Tests / Testes
- Troubleshooting / Solução de problemas
- Project structure / Estrutura do projeto
- Contributing / Contribuindo
- License / Licença

## About / Sobre

ProjetoTeste é uma aplicação exemplo em Laravel para gerenciar tarefas (tarefas CRUD), com suporte a:

- Listagem e filtros
- Criação/Edição/Visualização via modais AJAX
- Sistema de comentários por tarefa
- Status e prioridade das tarefas
- Autenticação básica (Laravel Breeze / Auth scaffolding)

Project purpose: provide a small, practical Laravel application with common features used in productivity apps and to serve as a base for testing and learning.

## Requirements / Requisitos

- PHP 8.0+ with common extensions (pdo, pdo_sqlite or pdo_mysql, mbstring, openssl, tokenizer, xml)
- Composer
- Node.js (16+) and npm
- SQLite (default) or MySQL/MariaDB (configured via `.env`)

On Windows (Laragon) ensure the PHP used by the web server/CLI has PDO and the chosen DB driver enabled.

## Installation / Instalação

1. Clone the repository and enter the project folder:

```bash
git clone <repo-url> ProjetoTeste
cd ProjetoTeste
```

2. Install PHP dependencies and Node packages:

```bash
composer install
npm install
```

3. Copy environment and generate app key:

```bash
cp .env.example .env
php artisan key:generate
```

4. Configure your database in `.env` (SQLite recommended for local dev):

- SQLite: ensure `DB_CONNECTION=sqlite` and that `database/database.sqlite` exists (touch it).
- MySQL/MariaDB: set `DB_CONNECTION=mysql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`.

## Database / Banco de dados

Run migrations to create tables (including `comments`):

```bash
php artisan migrate
```

If you encounter `could not find driver`:

- Enable the appropriate PDO extension in `php.ini` (e.g. `pdo_sqlite` for SQLite or `pdo_mysql` for MySQL).
- Restart web server / PHP-FPM and re-run `php artisan migrate`.

If you encounter `Class "CreateCommentsTable" not found` or similar: ensure there are no empty/invalid files in `database/migrations`. The migrator loads every file in that folder; an empty file will cause this error. Remove empty migration files or correct them.
