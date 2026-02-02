<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Exibir formulário de troca de senha
     */
    public function show()
    {
        return view('auth.trocar-senha');
    }

    /**
     * Processar troca de senha
     */
    public function update(Request $request)
    {
        $request->validate([
            'senha_atual' => 'required',
            'nova_senha' => 'required|min:4|max:10|confirmed',
        ], [
            'senha_atual.required' => 'A senha atual é obrigatória.',
            'nova_senha.required' => 'A nova senha é obrigatória.',
            'nova_senha.min' => 'A nova senha deve ter no mínimo 4 caracteres.',
            'nova_senha.max' => 'A nova senha deve ter no máximo 10 caracteres.',
            'nova_senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $usuario = Auth::user();

        // Verificar senha atual (suporta MD5 legado e bcrypt)
        $senhaAtualValida = false;
        
        if (Hash::check($request->senha_atual, $usuario->senha)) {
            $senhaAtualValida = true;
        } elseif (md5($request->senha_atual) === $usuario->senha) {
            $senhaAtualValida = true;
        }

        if (!$senhaAtualValida) {
            return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
        }

        // Verificar se a nova senha é diferente da atual
        if ($request->senha_atual === $request->nova_senha) {
            return back()->withErrors(['nova_senha' => 'A nova senha deve ser diferente da atual.']);
        }

        // Atualizar senha
        $usuario->senha = Hash::make($request->nova_senha);
        $usuario->trocar_senha = 0;
        $usuario->save();

        return redirect()
            ->route('admin.home')
            ->with('success', 'Senha alterada com sucesso!');
    }
}
