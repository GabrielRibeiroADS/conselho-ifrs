# 🎓 Sistema de Conselhos de Classe

Sistema web para gerenciamento de Conselhos de Classe desenvolvido em **Laravel 12** com PHP 8.2.

---

## 📋 Sobre o Projeto

O sistema permite gerenciar:

- **Unidades** - Cadastro de campus/unidades educacionais
- **Cursos** - Gerenciamento de cursos por unidade
- **Usuários** - Controle de usuários administrativos com papéis e permissões
- **Estudantes** - Cadastro completo de estudantes com matrículas
- **Conselhos de Classe** - Registro e acompanhamento de conselhos de classe
- **Papéis/Profissões** - Gestão de funções (Orientador, Psicólogo, Professor, etc.)

### 🔐 Sistema de Permissões

| Role | Descrição |
|------|-----------|
| `admin` | Acesso total ao sistema |
| `coordenador` | Gerenciamento de unidades, cursos, usuários e estudantes |
| `analista` | Visualização e análise de estudantes |

---

## ⚙️ Requisitos

- **PHP** >= 8.2
- **Composer** >= 2.x
- **Node.js** >= 18.x (com npm)
- **MySQL** / **MariaDB**

---

# 🅰️ OPÇÃO 1: Instalação COM Laragon

> Recomendado para Windows. O Laragon já vem com PHP, MySQL e Apache configurados.

## 1. Instale o Laragon

Baixe e instale o Laragon: https://laragon.org/download/

## 2. Clone o projeto na pasta do Laragon

```bash
cd C:\laragon\www
git clone https://github.com/seu-usuario/conselhos-laravel.git
cd conselhos-laravel
```

## 3. Instale as dependências

```bash
composer install
npm install
```

## 4. Configure o ambiente

```bash
copy .env.example .env
```

Edite o arquivo `.env`:

```env
APP_NAME="Conselhos de Classe"
APP_URL=http://localhost/conselhos-laravel/public

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=conselhos
DB_USERNAME=root
DB_PASSWORD=
```

## 5. Crie o banco de dados

Abra o **HeidiSQL** (botão direito no Laragon > HeidiSQL) e crie um banco chamado `conselhos`.

## 6. Configure a aplicação

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 7. Compile os assets

```bash
npm run build
```

## 8. Acesse o sistema

Inicie o Laragon (botão "Start All") e acesse:

| URL | Descrição |
|-----|-----------|
| http://localhost/conselhos-laravel/public | Página de login |
| http://localhost/conselhos-laravel/public/admin | Painel administrativo |

---

# 🅱️ OPÇÃO 2: Instalação SEM Laragon (Tradicional)

> Para quem já tem PHP, Composer, Node.js e MySQL instalados no sistema.

## 1. Clone o repositório

```bash
git clone https://github.com/seu-usuario/conselhos-laravel.git
cd conselhos-laravel
```

## 2. Instale as dependências

```bash
composer install
npm install
```

## 3. Configure o ambiente

```bash
cp .env.example .env
```

Edite o arquivo `.env`:

```env
APP_NAME="Conselhos de Classe"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=conselhos
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

## 4. Crie o banco de dados

Acesse o MySQL e crie o banco:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE conselhos;
EXIT;
```

## 5. Configure a aplicação

```bash
php artisan key:generate
php artisan migrate
php artisan db:seed
```

## 6. Execute o projeto

### Opção A: Setup Rápido

```bash
composer dev
```

Isso inicia servidor, queue, logs e Vite simultaneamente.

### Opção B: Manual (dois terminais)

**Terminal 1 - Servidor PHP:**
```bash
php artisan serve
```

**Terminal 2 - Vite (assets):**
```bash
npm run dev
```

## 7. Acesse o sistema

| URL | Descrição |
|-----|-----------|
| http://localhost:8000 | Página de login |
| http://localhost:8000/admin | Painel administrativo |

---

## 🧪 Testes

```bash
php artisan test
```

---

## 📁 Estrutura do Projeto

```
conselhos-laravel/
├── app/
│   ├── Http/Controllers/Admin/   # Controllers do admin
│   └── Models/                   # Models Eloquent
├── database/
│   ├── migrations/               # Estrutura do banco
│   └── seeders/                  # Dados iniciais
├── resources/views/admin/        # Views do painel
├── routes/web.php                # Rotas
└── public/assets/                # CSS, JS, imagens
```

---

## 🛠️ Tecnologias

- **Laravel 12** + **PHP 8.2**
- **Tailwind CSS 4** + **Vite 7**
- **Spatie Laravel Permission**
- **MySQL/MariaDB**

---

## 📄 Documentação Adicional

- [Documentação de Permissões](DOCUMENTACAO_PERMISSOES.md)
