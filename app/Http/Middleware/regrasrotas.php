<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class regrasrotas
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, string $rota): Response
    {
        try {
            $rotasAutorizadas = session()->get('usuario.acesso');

            if (!$rotasAutorizadas) {
                app('flasher')->addError('É necessário fazer login para acessar!');
                return redirect()->back();
            } elseif (in_array($rota, $rotasAutorizadas)) {
                return $next($request);
            } else {
                app('flasher')->addError('Você não tem autorização para acessar esta funcionalidade!');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            app('flasher')->addError('Houve um Erro Inesperado!!');
            return redirect()->back();
        }
    }
}
