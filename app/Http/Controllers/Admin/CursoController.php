<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Curso;
use App\Models\Unidade;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    /**
     * Listar todos os cursos
     */
    public function index(Request $request)
    {
        $unidades = Unidade::orderBy('nome')->get();
        $modalidades = Curso::modalidades();
        
        $query = Curso::with('unidade');
        
        // Filtro por unidade
        if ($request->filled('unidade') && $request->unidade != '-1') {
            $query->where('id_unidade', $request->unidade);
        }
        
        // Filtro por modalidade
        if ($request->filled('modalidade') && $request->modalidade != '%') {
            $query->where('modalidade', $request->modalidade);
        }
        
        $cursos = $query->orderBy('nome')->get();
        
        return view('admin.cursos.index', compact('cursos', 'unidades', 'modalidades'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        return view('admin.cursos.form', [
            'curso' => new Curso(),
            'titulo' => 'Novo Curso',
            'unidades' => Unidade::orderBy('nome')->get(),
            'modalidades' => Curso::modalidades(),
        ]);
    }

    /**
     * Salvar novo curso
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'modalidade' => 'required|string',
            'turno' => 'nullable|string|max:50',
            'ead' => 'boolean',
            'id_unidade' => 'required|exists:unidades,id',
        ], [
            'nome.required' => 'O nome do curso é obrigatório.',
            'modalidade.required' => 'A modalidade é obrigatória.',
            'id_unidade.required' => 'A unidade é obrigatória.',
            'id_unidade.exists' => 'A unidade selecionada não existe.',
        ]);

        // Converter checkbox
        $validated['ead'] = $request->has('ead') ? 1 : 0;

        Curso::create($validated);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso cadastrado com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Curso $curso)
    {
        return view('admin.cursos.form', [
            'curso' => $curso,
            'titulo' => 'Editar Curso',
            'unidades' => Unidade::orderBy('nome')->get(),
            'modalidades' => Curso::modalidades(),
        ]);
    }

    /**
     * Atualizar curso
     */
    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'modalidade' => 'required|string',
            'turno' => 'nullable|string|max:50',
            'ead' => 'boolean',
            'id_unidade' => 'required|exists:unidades,id',
        ], [
            'nome.required' => 'O nome do curso é obrigatório.',
            'modalidade.required' => 'A modalidade é obrigatória.',
            'id_unidade.required' => 'A unidade é obrigatória.',
            'id_unidade.exists' => 'A unidade selecionada não existe.',
        ]);

        // Converter checkbox
        $validated['ead'] = $request->has('ead') ? 1 : 0;

        $curso->update($validated);

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso atualizado com sucesso!');
    }

    /**
     * Excluir curso
     */
    public function destroy(Curso $curso)
    {
        // Verificar se há matrículas vinculadas
        if ($curso->matriculas()->count() > 0) {
            return redirect()
                ->route('admin.cursos.index')
                ->with('error', 'Não é possível excluir este curso pois há matrículas vinculadas.');
        }

        $curso->delete();

        return redirect()
            ->route('admin.cursos.index')
            ->with('success', 'Curso excluído com sucesso!');
    }
}
