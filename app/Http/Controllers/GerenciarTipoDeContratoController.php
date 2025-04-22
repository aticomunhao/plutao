<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarTipoDeContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tipos_de_contrato = DB::table('tp_contrato')->get();

        return view('tipos_de_contrato.gerenciar-tipos-de-contrato', compact('tipos_de_contrato'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tipos_de_contrato\incluir-tipo-de-contrato');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $nome = $request->input('nome');

            DB::table('tp_contrato')->insert(['nome' => $nome]);
            app('flasher')->addSuccess("Adicionado com Sucesso");
        } catch (Exception $exception) {
            app('flasher')->addError('Erro: ' . $exception->getCode() . " favor contatar o administrador.");
        } finally {
            return redirect()->route('index.tipos-de-contrato');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $tipo_de_contrato =  DB::table('tp_contrato')->where('id', '=', $id)->first();

        return view('tipos_de_contrato.editar-tipo-de-contrato', compact('tipo_de_contrato'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $nome = $request->input('nome');
            DB::table('tp_contrato')->where('id', $id)->update(['nome' => $nome]);
            app('flasher')->addSuccess('Editado com Sucesso');
        } catch (Exception $exception) {
            app('flasher')->addError('Erro: ' . $exception->getCode() . " favor contatar o administrador.");
        } finally {
            return redirect()->route('index.tipos-de-contrato');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            DB::table('tp_contrato')->where('id', $id)->delete();
            app('flasher')->addWarning('Deletado com Sucesso');
        } catch (Exception $exception) {
            app('flasher')->addError('Erro: ' . $exception->getCode() . " favor contatar o administrador.");
        } finally {
            return redirect()->route('index.tipos-de-contrato');
        }
    }
}
