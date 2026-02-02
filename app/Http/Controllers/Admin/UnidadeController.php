<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Unidade;
use Illuminate\Http\Request;

class UnidadeController extends Controller
{
    /**
     * Listar todas as unidades
     */
    public function index()
    {
        $unidades = Unidade::orderBy('nome')->get();
        
        return view('admin.unidades.index', compact('unidades'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.unidades.form', [
            'unidade' => new Unidade(),
            'titulo' => 'Nova Unidade'
        ]);
    }

    /**
     * Salvar nova unidade
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'email_suporte' => 'required|email|max:200',
            'pasta_base' => 'required|string|max:50',
            'unidade_gestora' => 'boolean',
            'usar_drive' => 'boolean',
            'conta_drive' => 'nullable|string|max:200',
            'acesso_drive' => 'nullable|string|max:50',
        ], [
            'nome.required' => 'O nome da unidade é obrigatório.',
            'email_suporte.required' => 'O e-mail de suporte é obrigatório.',
            'email_suporte.email' => 'Informe um e-mail válido.',
            'pasta_base.required' => 'A pasta para uploads é obrigatória.',
        ]);

        // Converter checkboxes
        $validated['unidade_gestora'] = $request->has('unidade_gestora') ? 1 : 0;
        $validated['usar_drive'] = $request->has('usar_drive') ? 1 : 0;

        Unidade::create($validated);

        return redirect()
            ->route('admin.unidades.index')
            ->with('success', 'Unidade cadastrada com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Unidade $unidade)
    {
        return view('admin.unidades.form', [
            'unidade' => $unidade,
            'titulo' => 'Editar Unidade'
        ]);
    }

    /**
     * Atualizar unidade
     */
    public function update(Request $request, Unidade $unidade)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:100',
            'email_suporte' => 'required|email|max:200',
            'pasta_base' => 'required|string|max:50',
            'unidade_gestora' => 'boolean',
            'usar_drive' => 'boolean',
            'conta_drive' => 'nullable|string|max:200',
            'acesso_drive' => 'nullable|string|max:50',
        ], [
            'nome.required' => 'O nome da unidade é obrigatório.',
            'email_suporte.required' => 'O e-mail de suporte é obrigatório.',
            'email_suporte.email' => 'Informe um e-mail válido.',
            'pasta_base.required' => 'A pasta para uploads é obrigatória.',
        ]);

        // Converter checkboxes
        $validated['unidade_gestora'] = $request->has('unidade_gestora') ? 1 : 0;
        $validated['usar_drive'] = $request->has('usar_drive') ? 1 : 0;

        $unidade->update($validated);

        return redirect()
            ->route('admin.unidades.index')
            ->with('success', 'Unidade atualizada com sucesso!');
    }

    /**
     * Excluir unidade
     */
    public function destroy(Unidade $unidade)
    {
        // Verificar se há cursos ou usuários vinculados
        if ($unidade->cursos()->count() > 0) {
            return redirect()
                ->route('admin.unidades.index')
                ->with('error', 'Não é possível excluir esta unidade pois há cursos vinculados.');
        }

        if ($unidade->usuarios()->count() > 0) {
            return redirect()
                ->route('admin.unidades.index')
                ->with('error', 'Não é possível excluir esta unidade pois há usuários vinculados.');
        }

        $unidade->delete();

        return redirect()
            ->route('admin.unidades.index')
            ->with('success', 'Unidade excluída com sucesso!');
    }
}
