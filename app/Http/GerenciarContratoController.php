<?php

namespace App\Http\Controllers;

use App\Models\pessoa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use File;
use DateTime;
use Illuminate\Support\Facades\Storage;

class GerenciarContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($idf)
    {
        $funcionario = DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->select('funcionarios.id', 'pessoas.nome_completo')
            ->where('funcionarios.id', '=', $idf)
            ->first();

        $situacao = DB::table('tp_demissao')
            ->select(
                'tp_demissao.motivo',
                'tp_demissao.id',
            )
            ->get();

        $contrato = DB::table('contrato as c')
            ->join('tp_contrato', 'tp_contrato.id', '=', 'c.tp_contrato')
            ->leftJoin('tp_demissao', 'tp_demissao.id', 'c.motivo')
            ->select(
                'c.id',
                'c.matricula',
                'c.dt_inicio',
                'c.dt_fim',
                'tp_contrato.nome',
                'c.id_tp_demissao',
                'c.id_funcionario',
                'c.admissao',
                'c.caminho',
                'tp_demissao.motivo AS motivo'
            )
            ->where('c.id_funcionario', $idf)
            ->get();

        foreach ($contrato as $contratos) {
            $dataDeHoje = new DateTime();
            $dataFormatada = $dataDeHoje->format('Y-m-d');
            $datadoBancoDeDados = new DateTime($contratos->dt_fim);
            $datadoBancoDeDadosFormatada = $datadoBancoDeDados->format('Y-m-d');
            $contratos->valido = ($dataFormatada <= $datadoBancoDeDadosFormatada) ? "Sim" : "Não";
        }

        return view('contrato.gerenciar-contrato', compact('contrato', 'funcionario', 'situacao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($idf)
    {
        $tipocontrato = DB::table('tp_contrato')->get();

        $funcionario = DB::table("funcionarios")
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->select('funcionarios.id', 'pessoas.nome_completo')
            ->where('funcionarios.id', $idf)
            ->first();

        return view('contrato.incluir-contrato', compact('tipocontrato', 'funcionario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $idf)
    {
        $funcionario = DB::table('funcionarios')
            ->leftJoin('pessoas', 'funcionarios.id_pessoa', '=', 'pessoas.id')
            ->leftJoin('contrato', 'funcionarios.id', 'contrato.id_funcionario')
            ->select('pessoas.nome_completo', 'pessoas.cpf', 'funcionarios.id', 'contrato.matricula')
            ->where('funcionarios.id', $idf)
            ->first();


        //dd($funcionario, $idf);

        if ($request->input('dt_inicio') > $request->input('dt_fim') && $request->input('dt_fim') != null) {
            //$caminho = $this->storeFile($request);
            app('flasher')->addError('A data inicial é maior que a data final');
            return redirect()->route('indexGerenciarContrato', ['id' => $idf]);
        } elseif ($funcionario && $funcionario->matricula == $request->input('matricula')) {
            $caminho = $this->storeFile($request ?? '');
            ;
            $data = [
                'tp_contrato' => $request->input('tipo_contrato'),
                'dt_inicio' => $request->input('dt_inicio'),
                'dt_fim' => $request->input('dt_fim'),
                'id_funcionario' => $idf,
                'caminho' => $caminho,
                'matricula' => $request->input('matricula'),
                'admissao' => '0',
            ];

            DB::table('contrato')->insert($data);
            app('flasher')->addSuccess('O novo cadastro do Contrato foi realizado com sucesso.');
            return redirect()->route('indexGerenciarContrato', ['id' => $idf]);
        } else {
            $caminho = $this->storeFile($request ?? '');
            ;
            $data = [
                'tp_contrato' => $request->input('tipo_contrato'),
                'dt_inicio' => $request->input('dt_inicio'),
                'dt_fim' => $request->input('dt_fim'),
                'id_funcionario' => $idf,
                'caminho' => $caminho,
                'matricula' => $request->input('matricula'),
                'admissao' => '1',
            ];

            DB::table('contrato')->insert($data);
            app('flasher')->addSuccess('O cadastro do Contrato foi realizado com sucesso.');
            return redirect()->route('indexGerenciarContrato', ['id' => $idf]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $contrato = DB::table('contrato')->where('id', $id)->first();
        $funcionario = $this->getFuncionarioData($contrato->id_funcionario);
        $tipocontrato = DB::table('tp_contrato')->get();

        return view('contrato.editar-contrato', compact('contrato', 'funcionario', 'tipocontrato'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contrato = DB::table('contrato')->where('id', $id)->first();
        $funcionario = $this->getFuncionarioData($contrato->id_funcionario);

        if ($request->input('dt_inicio') > $request->input('dt_fim')) {
            app('flasher')->addError('A data inicial é maior que a data final');
            return redirect()->route('indexGerenciarContrato', ['id' => $contrato->id_funcionario]);
        } elseif ($request->file('ficheiroNovo') == null) {
            $this->updateContratoWithoutFile($contrato, $request);
        } elseif ($request->hasFile('ficheiroNovo')) {
            $this->updateContratoWithFile($contrato, $request);
        } else {
            app('flasher')->addWarning('O cadastro do Contrato foi Alterado com Sucesso.');
        }

        return redirect()->route('indexGerenciarContrato', ['id' => $contrato->id_funcionario]);
    }

    private function updateContratoWithFile($contrato, Request $request)
    {
        $nomeArquivo = $request->file('ficheiroNovo')->getClientOriginalName();
        $novoCaminho = $request->file('ficheiroNovo')->storeAs('public/images', $nomeArquivo);

        if ($novoCaminho) {
            Storage::delete($contrato->caminho); // Remove o arquivo antigo

            DB::table('contrato')
                ->where('id', $contrato->id)
                ->update([
                    'tp_contrato' => $request->input('tipo_contrato'),
                    'dt_inicio' => $request->input('dt_inicio'),
                    'caminho' => 'storage/images/' . $nomeArquivo,
                    'matricula' => $request->input('matricula'),
                ]);
        }
    }



    // Métodos Auxiliares

    private function storeFile(Request $request)
    {
        if ($request->hasFile('ficheiro')) {
            $caminho = $request->file('ficheiro')->storeAs('public/images', $request->file('ficheiro')->getClientOriginalName());
            return 'storage/images/' . $request->file('ficheiro')->getClientOriginalName();
        }
    }

    private function updateContratoWithoutFile($contrato, Request $request)
    {
        DB::table('contrato')
            ->where('id', $contrato->id)
            ->update([
                'tp_contrato' => $request->input('tipo_contrato'),
                'dt_inicio' => $request->input('dt_inicio'),
                'matricula' => $request->input('matricula'),
            ]);
    }


    private function getFuncionarioData($funcionarioId)
    {
        return DB::table('funcionarios')
            ->join('pessoas', 'pessoas.id', '=', 'funcionarios.id_pessoa')
            ->select('pessoas.cpf', 'pessoas.nome_completo', 'funcionarios.id')
            ->where('funcionarios.id', $funcionarioId)
            ->first();
    }
    public function destroy(string $id)
    {
        $contrato = DB::table('contrato')->where('id', $id)->first();
        //Storage::delete($contrato->caminho);
        DB::table('contrato')->where('id', $id)->delete();

        app('flasher')->addWarning('O cadastro do Contrato foi Removido com Sucesso.');
        return redirect()->back();
    }
    public function inativar($idf, Request $request)
    {
        //dd($request->all(), $idf);
        DB::table('contrato AS a')
            ->where('id', $idf)
            ->update([
                'a.dt_fim' => $request->input('dt_fim_inativacao'),
                'a.motivo' => $request->input('motivo_inativar')
            ]);


        app('flasher')->addSuccess('O cadastro do funcionário foi inativado com Sucesso.');
        return redirect()->back();
    }
}
