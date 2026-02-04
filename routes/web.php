<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UnidadeController;
use App\Http\Controllers\Admin\CursoController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\EstudanteController;
use App\Http\Controllers\Admin\PapelController;
use App\Http\Controllers\Admin\ConselhoController;
use App\Http\Controllers\Admin\ReuniaoController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/

// Redireciona raiz para login
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Rota de Troca de Senha Obrigatória
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/trocar-senha', [PasswordController::class, 'show'])->name('password.change');
    Route::post('/trocar-senha', [PasswordController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Rotas Admin (Protegidas por auth + verificação de senha)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'password.changed'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard/Home
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard');
    
    // CRUD Unidades
    Route::resource('unidades', UnidadeController::class)->except(['show']);
    
    // CRUD Cursos
    Route::resource('cursos', CursoController::class)->except(['show']);

    // CRUD Papéis/Profissões
    Route::resource('papeis', PapelController::class)->except(['show'])
        ->parameters(['papeis' => 'papel']);
    
    // CRUD Usuários
    Route::resource('usuarios', UsuarioController::class)->except(['show']);
    Route::post('usuarios/{usuario}/reset-password', [UsuarioController::class, 'resetPassword'])->name('usuarios.reset-password');
    
    // CRUD Estudantes
    Route::resource('estudantes', EstudanteController::class);
    Route::get('estudantes/buscar-por-matricula', [EstudanteController::class, 'buscarPorMatricula'])->name('estudantes.buscar-por-matricula');

    // CRUD Conselhos
    Route::resource('conselhos', ConselhoController::class);

    // Reuniões de Conselhos
    Route::prefix('conselhos/{conselho}/reunioes')->name('reunioes.')->group(function () {
        Route::get('/', [ReuniaoController::class, 'index'])->name('index');
        Route::post('/gerar', [ReuniaoController::class, 'gerar'])->name('gerar');
        Route::get('/{reuniao}', [ReuniaoController::class, 'show'])->name('show');
        Route::get('/{reuniao}/edit', [ReuniaoController::class, 'edit'])->name('edit');
        Route::put('/{reuniao}', [ReuniaoController::class, 'update'])->name('update');
        Route::post('/{reuniao}/finalizar', [ReuniaoController::class, 'finalizar'])->name('finalizar');
        
        // Avaliações
        Route::post('/{reuniao}/avaliacoes/{avaliacao}', [ReuniaoController::class, 'salvarAvaliacao'])->name('avaliacoes.salvar');
        
        // Encaminhamentos
        Route::post('/{reuniao}/avaliacoes/{avaliacao}/encaminhamentos', [ReuniaoController::class, 'adicionarEncaminhamento'])->name('encaminhamentos.adicionar');
        Route::put('/{reuniao}/encaminhamentos/{encaminhamento}', [ReuniaoController::class, 'atualizarEncaminhamento'])->name('encaminhamentos.atualizar');
        Route::delete('/{reuniao}/encaminhamentos/{encaminhamento}', [ReuniaoController::class, 'excluirEncaminhamento'])->name('encaminhamentos.excluir');
    });
});
