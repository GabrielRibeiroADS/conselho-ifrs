# Documentacao - Permissoes e Papeis (Projeto Conselhos)

## Contexto e ambiente
- Projeto em Laravel 12 (PHP 8.2), rodando em Laragon com MySQL/MariaDB.
- Banco configurado via `.env`:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_DATABASE=dbae`
  - `DB_USERNAME=root`
  - `DB_PASSWORD=`
- A autenticacao usa o model `App\Models\Usuario` com tabela `usuarios_admin`.

## Estrutura de permissao adotada
- Uso do pacote **spatie/laravel-permission** (tabelas `roles`, `permissions` e pivots).
- Papeis/Profissoes sao modelados como **roles**.
- Permissoes tecnicas seguem o padrao `recurso.acao` (ex.: `usuarios.edit`, `estudantes.index`).

### O que e o Spatie (laravel-permission)
- Pacote oficial da comunidade Laravel para **controle de acesso** via **roles** e **permissions**.
- Fornece models, migrations e helpers (`assignRole`, `syncRoles`, `@can`) para gerenciar quem pode acessar cada tela/acao.
- Padroniza o controle de acesso e facilita a manutencao do sistema.

## O que foi implementado
1) **CRUD de Papeis/Profissoes** (roles)
   - Controller: `app/Http/Controllers/Admin/PapelController.php`
   - Views:
     - `resources/views/admin/papeis/index.blade.php`
     - `resources/views/admin/papeis/form.blade.php`
   - Rotas:
     - `routes/web.php` (resource `admin/papeis`)

2) **Atribuicao de papeis aos usuarios**
   - Formulario de usuario agora exibe apenas **Papeis/Profissoes**.
   - Ao salvar, o usuario recebe roles via `syncRoles()`.
   - Arquivos:
     - `app/Http/Controllers/Admin/UsuarioController.php`
     - `resources/views/admin/usuarios/form.blade.php`
     - `resources/views/admin/usuarios/index.blade.php`

3) **Seeders para permissao e papeis**
   - `database/seeders/PermissionSeeder.php`
     - Cadastra permissoes `*.index/create/edit/delete` e roles base (`admin`, `coordenador`, `analista`).
     - Inclui permissoes `papeis.*`.
   - `database/seeders/ProfissaoSeeder.php`
     - Pre-cadastra papeis/profissoes:
       - Orientador Estudantil
       - Psicologo
       - Assistente Social
       - Professor Conselheiro
       - Diretor
       - Coordenador
   - `database/seeders/DatabaseSeeder.php` chama ambos.

4) **Menu e UI**
   - Item "Papeis/Profissoes" no menu lateral (visivel para admin ou permissao `papeis.index`).
     - `resources/views/components/menu-lateral.blade.php`
   - Ajuste visual dos botoes de acao (usuarios e estudantes):
     - `btn-outline-success` + icones verdes e brancos no hover.
     - `resources/views/admin/usuarios/index.blade.php`
     - `resources/views/admin/estudantes/index.blade.php`
     - `public/assets/css/custom.css`

## O que passou a ser utilizado
- **Roles** do Spatie para representar funcao/profissao.
- **Permissoes** do Spatie para controle fino (ainda nao totalmente aplicado a todas as telas).
- **Seeders** para pre-cadastro de papeis e permissoes.

### O que sao Seeders
- Seeders sao classes do Laravel que **preenchem o banco** com dados iniciais.
- Diferem de migrations: migrations criam/alteram a estrutura; seeders inserem dados.
- Neste projeto, seeders sao usados para cadastrar permissões e papeis padrao.

## Como rodar os seeders
```bash
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=ProfissaoSeeder
```

## Acesso no Laragon
- URL tipica do projeto:
  - `http://localhost/conselhos-laravel/public`
  - login: `http://localhost/conselhos-laravel/public/login`
  - admin: `http://localhost/conselhos-laravel/public/admin`

## Observacoes importantes
- As tabelas de dominio (ex.: `usuarios_admin`, `estudantesv2`, `unidades`) ja existem no banco e nao possuem migrations neste repo.
- Algumas telas ainda usam flags antigas (`habilita_*`) para liberar acesso no menu. A proxima etapa seria migrar todo o controle de acesso para `@can(...)` do Spatie.
