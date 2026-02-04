<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Conselho;
use App\Models\Reuniao;
use App\Models\ReuniaoEstudante;
use App\Models\Encaminhamento;
use Illuminate\Http\Request;

class ReuniaoController extends Controller
{
    /**
     * Listar reuniões de um conselho
     */
    public function index(Conselho $conselho)
    {
        $conselho->load(['curso', 'reunioes', 'estudantes']);

        return view('admin.reunioes.index', compact('conselho'));
    }

    /**
     * Gerar reuniões automaticamente para o conselho
     */
    public function gerar(Conselho $conselho)
    {
        // Verificar se já existem reuniões
        if ($conselho->reunioes()->count() > 0) {
            return redirect()
                ->route('admin.reunioes.index', $conselho)
                ->with('warning', 'Este conselho já possui reuniões cadastradas.');
        }

        // Verificar se há estudantes no conselho
        if ($conselho->estudantes()->count() === 0) {
            return redirect()
                ->route('admin.reunioes.index', $conselho)
                ->with('error', 'Adicione estudantes ao conselho antes de gerar as reuniões.');
        }

        // Gerar reuniões
        Reuniao::gerarParaConselho($conselho);

        return redirect()
            ->route('admin.reunioes.index', $conselho)
            ->with('success', 'Reuniões geradas com sucesso!');
    }

    /**
     * Exibir detalhes de uma reunião (com avaliações)
     */
    public function show(Conselho $conselho, Reuniao $reuniao)
    {
        // Garantir que a reunião pertence ao conselho
        if ($reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        $reuniao->load(['avaliacoes.estudante', 'avaliacoes.encaminhamentos']);

        // Carregar estudantes do conselho que ainda não têm avaliação nesta reunião
        $estudantesDoConselho = $conselho->estudantes()->get();
        $estudantesComAvaliacao = $reuniao->avaliacoes->pluck('estudante_id')->toArray();

        // Criar avaliações para estudantes que ainda não têm
        foreach ($estudantesDoConselho as $estudante) {
            if (!in_array($estudante->id, $estudantesComAvaliacao)) {
                ReuniaoEstudante::create([
                    'reuniao_id' => $reuniao->id,
                    'estudante_id' => $estudante->id,
                    'presente' => true,
                ]);
            }
        }

        // Recarregar avaliações
        $reuniao->load(['avaliacoes.estudante', 'avaliacoes.encaminhamentos']);

        return view('admin.reunioes.show', compact('conselho', 'reuniao'));
    }

    /**
     * Formulário de edição da reunião
     */
    public function edit(Conselho $conselho, Reuniao $reuniao)
    {
        if ($reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        return view('admin.reunioes.edit', compact('conselho', 'reuniao'));
    }

    /**
     * Atualizar dados da reunião
     */
    public function update(Request $request, Conselho $conselho, Reuniao $reuniao)
    {
        if ($reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:100',
            'data_reuniao' => 'nullable|date',
            'status' => 'required|in:pendente,em_andamento,finalizada',
            'observacoes' => 'nullable|string',
        ]);

        $reuniao->update($validated);

        return redirect()
            ->route('admin.reunioes.show', [$conselho, $reuniao])
            ->with('success', 'Reunião atualizada com sucesso!');
    }

    /**
     * Salvar avaliação de um estudante
     */
    public function salvarAvaliacao(Request $request, Conselho $conselho, Reuniao $reuniao, ReuniaoEstudante $avaliacao)
    {
        if ($reuniao->conselho_id !== $conselho->id || $avaliacao->reuniao_id !== $reuniao->id) {
            abort(404);
        }

        $validated = $request->validate([
            'avaliacao' => 'nullable|string',
            'presente' => 'boolean',
        ]);

        $avaliacao->update([
            'avaliacao' => $validated['avaliacao'] ?? null,
            'presente' => $validated['presente'] ?? true,
        ]);

        return redirect()
            ->route('admin.reunioes.show', [$conselho, $reuniao])
            ->with('success', 'Avaliação salva com sucesso!');
    }

    /**
     * Adicionar encaminhamento a uma avaliação
     */
    public function adicionarEncaminhamento(Request $request, Conselho $conselho, Reuniao $reuniao, ReuniaoEstudante $avaliacao)
    {
        if ($reuniao->conselho_id !== $conselho->id || $avaliacao->reuniao_id !== $reuniao->id) {
            abort(404);
        }

        $validated = $request->validate([
            'descricao' => 'required|string',
        ]);

        Encaminhamento::create([
            'reuniao_estudante_id' => $avaliacao->id,
            'descricao' => $validated['descricao'],
            'status' => 'pendente',
        ]);

        return redirect()
            ->route('admin.reunioes.show', [$conselho, $reuniao])
            ->with('success', 'Encaminhamento adicionado com sucesso!');
    }

    /**
     * Atualizar status de um encaminhamento
     */
    public function atualizarEncaminhamento(Request $request, Conselho $conselho, Reuniao $reuniao, Encaminhamento $encaminhamento)
    {
        // Verificar se o encaminhamento pertence à reunião
        $avaliacao = $encaminhamento->reuniaoEstudante;
        if (!$avaliacao || $avaliacao->reuniao_id !== $reuniao->id || $reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => 'required|in:pendente,em_andamento,concluido,cancelado',
            'observacoes' => 'nullable|string',
        ]);

        $encaminhamento->update($validated);

        if ($validated['status'] === 'concluido') {
            $encaminhamento->update(['data_conclusao' => now()]);
        }

        return redirect()
            ->route('admin.reunioes.show', [$conselho, $reuniao])
            ->with('success', 'Encaminhamento atualizado com sucesso!');
    }

    /**
     * Excluir encaminhamento
     */
    public function excluirEncaminhamento(Conselho $conselho, Reuniao $reuniao, Encaminhamento $encaminhamento)
    {
        $avaliacao = $encaminhamento->reuniaoEstudante;
        if (!$avaliacao || $avaliacao->reuniao_id !== $reuniao->id || $reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        $encaminhamento->delete();

        return redirect()
            ->route('admin.reunioes.show', [$conselho, $reuniao])
            ->with('success', 'Encaminhamento excluído com sucesso!');
    }

    /**
     * Finalizar reunião
     */
    public function finalizar(Conselho $conselho, Reuniao $reuniao)
    {
        if ($reuniao->conselho_id !== $conselho->id) {
            abort(404);
        }

        $reuniao->update(['status' => 'finalizada']);

        return redirect()
            ->route('admin.reunioes.index', $conselho)
            ->with('success', 'Reunião finalizada com sucesso!');
    }
}
