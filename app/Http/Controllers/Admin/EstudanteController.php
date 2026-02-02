<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudante;
use App\Models\Unidade;
use App\Models\Curso;
use Illuminate\Http\Request;

class EstudanteController extends Controller
{
    /**
     * Listar todos os estudantes
     */
    public function index(Request $request)
    {
        $unidades = Unidade::orderBy('nome')->get();
        
        $query = Estudante::with(['matriculas.curso', 'matriculas.curso.unidade']);
        
        // Filtro por nome
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%' . $request->nome . '%');
        }
        
        // Filtro por CPF
        if ($request->filled('cpf')) {
            $query->where('cpf', 'like', '%' . $request->cpf . '%');
        }
        
        $estudantes = $query->orderBy('nome')->paginate(25);
        
        return view('admin.estudantes.index', compact('estudantes', 'unidades'));
    }

    /**
     * Exibir detalhes do estudante
     */
    public function show(Estudante $estudante)
    {
        $estudante->load(['matriculas.curso', 'matriculas.curso.unidade']);
        
        return view('admin.estudantes.show', compact('estudante'));
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $unidades = Unidade::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        
        return view('admin.estudantes.form', [
            'estudante' => new Estudante(),
            'titulo' => 'Novo Estudante',
            'unidades' => $unidades,
            'cursos' => $cursos,
        ]);
    }

    /**
     * Salvar novo estudante
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'cpf' => 'required|string|max:14|unique:estudantesv2,cpf',
            'email' => 'nullable|email|max:200',
            'telefone' => 'nullable|string|max:20',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $estudante = Estudante::create($validated);

        return redirect()
            ->route('admin.estudantes.show', $estudante)
            ->with('success', 'Estudante cadastrado com sucesso!');
    }

    /**
     * Formulário de edição
     */
    public function edit(Estudante $estudante)
    {
        $unidades = Unidade::orderBy('nome')->get();
        $cursos = Curso::orderBy('nome')->get();
        
        return view('admin.estudantes.form', [
            'estudante' => $estudante,
            'titulo' => 'Editar Estudante',
            'unidades' => $unidades,
            'cursos' => $cursos,
        ]);
    }

    /**
     * Atualizar estudante
     */
    public function update(Request $request, Estudante $estudante)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:200',
            'cpf' => 'required|string|max:14|unique:estudantesv2,cpf,' . $estudante->id,
            'email' => 'nullable|email|max:200',
            'telefone' => 'nullable|string|max:20',
        ], [
            'nome.required' => 'O nome é obrigatório.',
            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.unique' => 'Este CPF já está cadastrado.',
        ]);

        $estudante->update($validated);

        return redirect()
            ->route('admin.estudantes.show', $estudante)
            ->with('success', 'Estudante atualizado com sucesso!');
    }

    /**
     * Excluir estudante
     */
    public function destroy(Estudante $estudante)
    {
        // Verificar se há matrículas vinculadas
        if ($estudante->matriculas()->count() > 0) {
            return redirect()
                ->route('admin.estudantes.index')
                ->with('error', 'Não é possível excluir este estudante pois há matrículas vinculadas.');
        }

        $estudante->delete();

        return redirect()
            ->route('admin.estudantes.index')
            ->with('success', 'Estudante excluído com sucesso!');
    }

    /**
     * Buscar estudante por matrícula (AJAX)
     */
    public function buscarPorMatricula(Request $request)
    {
        $matricula = $request->get('matricula');
        
        if (!$matricula) {
            return response()->json(['error' => 'Matrícula não informada'], 400);
        }
        
        $estudante = Estudante::whereHas('matriculas', function ($q) use ($matricula) {
            $q->where('no_matricula', $matricula);
        })->with('matriculas.curso')->first();
        
        if (!$estudante) {
            return response()->json(['error' => 'Estudante não encontrado'], 404);
        }
        
        return response()->json($estudante);
    }
}
