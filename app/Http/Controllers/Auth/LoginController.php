<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Exibir formulário de login
     */
    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }
        
        return view('auth.login');
    }

    /**
     * Processar autenticação
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'senha' => 'required',
        ], [
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'senha.required' => 'A senha é obrigatória.',
        ]);

        // Buscar usuário pelo e-mail
        $usuario = Usuario::where('email', $credentials['email'])->first();

        if (!$usuario) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Usuário não encontrado.']);
        }

        // Verificar senha (suporta MD5 legado e bcrypt)
        $senhaValida = false;
        $senhaArmazenada = $usuario->senha;
        
        // Verificar se a senha armazenada é bcrypt (começa com $2y$ ou $2a$)
        $isBcrypt = str_starts_with($senhaArmazenada, '$2y$') || str_starts_with($senhaArmazenada, '$2a$');
        
        if ($isBcrypt) {
            // Senha já é bcrypt
            if (Hash::check($credentials['senha'], $senhaArmazenada)) {
                $senhaValida = true;
            }
        } else {
            // Senha é MD5 (legado) - hash tem 32 caracteres
            if (md5($credentials['senha']) === $senhaArmazenada) {
                $senhaValida = true;
                
                // Migrar para bcrypt
                $usuario->senha = Hash::make($credentials['senha']);
                $usuario->trocar_senha = 1; // Forçar troca de senha
                $usuario->save();
            }
        }

        if (!$senhaValida) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['senha' => 'Senha incorreta.']);
        }

        // Autenticar
        Auth::login($usuario, $request->boolean('lembrar'));

        $request->session()->regenerate();

        // Redirecionar para troca de senha se necessário
        if ($usuario->precisaTrocarSenha()) {
            return redirect()->route('password.change');
        }

        return redirect()->intended(route('admin.home'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
