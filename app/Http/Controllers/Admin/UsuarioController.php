<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    /**
     * Listar todos os usuários
     */
    public function index(Request $request)
    {
        $usuarioLogado = auth()->user();
        $unidades = Unidade::orderBy('nome')->get();
        
        $query = Usuario::with(['unidade', 'roles']);
        
        // Se não for da unidade gestora, mostrar apenas da própria unidade
        if (!$usuarioLogado->unidade || !$usuarioLogado->unidade->isGestora()) {
            $query->where('id_unidade', $usuarioLogado->id_unidade);
        } else {
            // Filtro por unidade para gestores
            if ($request->filled('unidade') && $request->unidade != '-1') {
                $query->where('id_unidade', $request->unidade);
            }
        }
        
        $usuarios = $query->orderBy('nome')->get();
        
        return view('admin.usuarios.index', compact('usuarios', 'unidades', 'usuarioLogado'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $usuarioLogado = auth()->user();
        
        // Se não for da unidade gestora, só pode criar na própria unidade
        if (!$usuarioLogado->unidade || !$usuarioLogado->unidade->isGestora()) {
            $unidades = Unidade::where('id', $usuarioLogado->id_unidade)->get();
        } else {
            $unidades = Unidade::orderBy('nome')->get();
        }

        $papeis = Role::orderBy('name')->get();
        
        return view('admin.usuarios.form', [
            'usuario' => new Usuario(),
            'titulo' => 'Novo Usuário',
            'unidades' => $unidades,
            'papeis' => $papeis,
        ]);
    }

    /**
     * Salvar novo usuário
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'email' => 'required|email|max:200|unique:usuarios_admin,email',
            'senha' => 'required|string|min:6',
            'id_unidade' => 'required|exists:unidades,id',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'senha.required' => 'A senha é obrigatória.',
            'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'id_unidade.required' => 'A unidade é obrigatória.',
        ]);

        // Preparar dados
        $validated['senha'] = Hash::make($validated['senha']);
        $validated['trocar_senha'] = 1; // Forçar troca de senha no primeiro acesso
        
        $usuario = Usuario::create($validated);

        $roles = Role::whereIn('id', $validated['roles'] ?? [])->get();
        $usuario->syncRoles($roles);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário cadastrado com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Usuario $usuario)
    {
        $usuarioLogado = auth()->user();
        
        // Se não for da unidade gestora, só pode editar da própria unidade
        if (!$usuarioLogado->unidade || !$usuarioLogado->unidade->isGestora()) {
            if ($usuario->id_unidade != $usuarioLogado->id_unidade) {
                abort(403, 'Você não tem permissão para editar este usuário.');
            }
            $unidades = Unidade::where('id', $usuarioLogado->id_unidade)->get();
        } else {
            $unidades = Unidade::orderBy('nome')->get();
        }

        $papeis = Role::orderBy('name')->get();
        
        return view('admin.usuarios.form', [
            'usuario' => $usuario,
            'titulo' => 'Editar Usuário',
            'unidades' => $unidades,
            'papeis' => $papeis,
        ]);
    }

    /**
     * Atualizar usuário
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'email' => ['required', 'email', 'max:200', Rule::unique('usuarios_admin')->ignore($usuario->id)],
            'senha' => 'nullable|string|min:6',
            'id_unidade' => 'required|exists:unidades,id',
            'roles' => 'nullable|array',
            'roles.*' => 'integer|exists:roles,id',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um e-mail válido.',
            'email.unique' => 'Este e-mail já está em uso.',
            'senha.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'id_unidade.required' => 'A unidade é obrigatória.',
        ]);

        // Se senha foi informada, criptografar
        if (!empty($validated['senha'])) {
            $validated['senha'] = Hash::make($validated['senha']);
        } else {
            unset($validated['senha']);
        }
        
        $usuario->update($validated);

        $roles = Role::whereIn('id', $validated['roles'] ?? [])->get();
        $usuario->syncRoles($roles);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Excluir usuário
     */
    public function destroy(Usuario $usuario)
    {
        $usuarioLogado = auth()->user();
        
        // Não permitir excluir a si mesmo
        if ($usuario->id == $usuarioLogado->id) {
            return redirect()
                ->route('admin.usuarios.index')
                ->with('error', 'Você não pode excluir seu próprio usuário.');
        }

        $usuario->delete();

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', 'Usuário excluído com sucesso!');
    }

    /**
     * Resetar senha do usuário
     */
    public function resetPassword(Usuario $usuario)
    {
        // Gerar nova senha aleatória
        $novaSenha = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
        
        $usuario->update([
            'senha' => Hash::make($novaSenha),
            'trocar_senha' => 1,
        ]);

        return redirect()
            ->route('admin.usuarios.index')
            ->with('success', "Senha resetada com sucesso! Nova senha: {$novaSenha}");
    }
}
