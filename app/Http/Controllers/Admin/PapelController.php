<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PapelController extends Controller
{
    /**
     * Listar papéis (profissões)
     */
    public function index()
    {
        $papeis = Role::withCount('permissions')
            ->orderBy('name')
            ->get();

        return view('admin.papeis.index', compact('papeis'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $permissoes = Permission::orderBy('name')->get();

        return view('admin.papeis.form', [
            'papel' => new Role(),
            'permissoes' => $permissoes,
            'titulo' => 'Novo Papel/Profissão',
        ]);
    }

    /**
     * Salvar novo papel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ], [
            'name.required' => 'O nome do papel é obrigatório.',
            'name.unique' => 'Este papel já existe.',
        ]);

        $papel = Role::create([
            'name' => $validated['name'],
            'guard_name' => 'web',
        ]);

        $papel->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.papeis.index')
            ->with('success', 'Papel cadastrado com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Role $papel)
    {
        $permissoes = Permission::orderBy('name')->get();

        return view('admin.papeis.form', [
            'papel' => $papel,
            'permissoes' => $permissoes,
            'titulo' => 'Editar Papel/Profissão',
        ]);
    }

    /**
     * Atualizar papel
     */
    public function update(Request $request, Role $papel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $papel->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ], [
            'name.required' => 'O nome do papel é obrigatório.',
            'name.unique' => 'Este papel já existe.',
        ]);

        $papel->update([
            'name' => $validated['name'],
        ]);

        $papel->syncPermissions($validated['permissions'] ?? []);

        return redirect()
            ->route('admin.papeis.index')
            ->with('success', 'Papel atualizado com sucesso!');
    }

    /**
     * Excluir papel
     */
    public function destroy(Role $papel)
    {
        $papel->delete();

        return redirect()
            ->route('admin.papeis.index')
            ->with('success', 'Papel excluído com sucesso!');
    }
}
