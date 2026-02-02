<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conselho;
use App\Models\Curso;
use App\Models\Estudante;
use Illuminate\Http\Request;

class ConselhoController extends Controller
{
    /**
     * Listar conselhos
     */
    public function index(Request $request)
    {
        $cursos = Curso::orderBy('nome')->get();

        $query = Conselho::with(['curso', 'estudantes']);

        if ($request->filled('curso') && $request->curso != '-1') {
            $query->where('id_curso', $request->curso);
        }

        if ($request->filled('ano')) {
            $query->where('ano', $request->ano);
        }

        if ($request->filled('tipo') && $request->tipo != '-1') {
            $query->where('tipo', $request->tipo);
        }

        $conselhos = $query->orderBy('ano', 'desc')
            ->orderBy('semestre', 'desc')
            ->paginate(25);

        return view('admin.conselhos.index', compact('conselhos', 'cursos'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $cursos = Curso::orderBy('nome')->get();
        $estudantes = Estudante::orderBy('nome')->get();

        return view('admin.conselhos.form', [
            'conselho' => new Conselho(),
            'cursos' => $cursos,
            'estudantes' => $estudantes,
            'titulo' => 'Novo Conselho',
        ]);
    }

    /**
     * Salvar novo conselho
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_curso' => 'required|exists:cursos,id',
            'ano' => 'required|integer|min:2000|max:2100',
            'tipo' => 'required|in:anual,semestral',
            'semestre' => 'nullable|integer|in:1,2',
            'observacoes' => 'nullable|string',
            'estudantes' => 'nullable|array',
            'estudantes.*' => 'integer|exists:estudantesv2,id',
        ], [
            'id_curso.required' => 'O curso é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'tipo.required' => 'O tipo é obrigatório.',
        ]);

        // Se tipo for semestral, exigir semestre
        if ($validated['tipo'] === 'semestral' && empty($validated['semestre'])) {
            return back()->withErrors(['semestre' => 'O semestre é obrigatório para conselhos semestrais.'])->withInput();
        }

        // Se tipo for anual, limpar semestre
        if ($validated['tipo'] === 'anual') {
            $validated['semestre'] = null;
        }

        $conselho = Conselho::create($validated);

        // Sincronizar estudantes
        if (!empty($validated['estudantes'])) {
            $conselho->estudantes()->sync($validated['estudantes']);
        }

        return redirect()
            ->route('admin.conselhos.index')
            ->with('success', 'Conselho cadastrado com sucesso!');
    }

    /**
     * Visualizar conselho
     */
    public function show(Conselho $conselho)
    {
        $conselho->load(['curso', 'estudantes']);

        return view('admin.conselhos.show', compact('conselho'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Conselho $conselho)
    {
        $cursos = Curso::orderBy('nome')->get();
        $estudantes = Estudante::orderBy('nome')->get();

        return view('admin.conselhos.form', [
            'conselho' => $conselho,
            'cursos' => $cursos,
            'estudantes' => $estudantes,
            'titulo' => 'Editar Conselho',
        ]);
    }

    /**
     * Atualizar conselho
     */
    public function update(Request $request, Conselho $conselho)
    {
        $validated = $request->validate([
            'id_curso' => 'required|exists:cursos,id',
            'ano' => 'required|integer|min:2000|max:2100',
            'tipo' => 'required|in:anual,semestral',
            'semestre' => 'nullable|integer|in:1,2',
            'observacoes' => 'nullable|string',
            'estudantes' => 'nullable|array',
            'estudantes.*' => 'integer|exists:estudantesv2,id',
        ], [
            'id_curso.required' => 'O curso é obrigatório.',
            'ano.required' => 'O ano é obrigatório.',
            'tipo.required' => 'O tipo é obrigatório.',
        ]);

        // Se tipo for semestral, exigir semestre
        if ($validated['tipo'] === 'semestral' && empty($validated['semestre'])) {
            return back()->withErrors(['semestre' => 'O semestre é obrigatório para conselhos semestrais.'])->withInput();
        }

        // Se tipo for anual, limpar semestre
        if ($validated['tipo'] === 'anual') {
            $validated['semestre'] = null;
        }

        $conselho->update($validated);

        // Sincronizar estudantes
        $conselho->estudantes()->sync($validated['estudantes'] ?? []);

        return redirect()
            ->route('admin.conselhos.index')
            ->with('success', 'Conselho atualizado com sucesso!');
    }

    /**
     * Excluir conselho
     */
    public function destroy(Conselho $conselho)
    {
        $conselho->delete();

        return redirect()
            ->route('admin.conselhos.index')
            ->with('success', 'Conselho excluído com sucesso!');
    }
}
