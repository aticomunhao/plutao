<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GerenciarCargosController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $pesquisa = request('pesquisa');

        // Confere se alguma pesquisa esta sendo feita e busca os dados em banco
        if ($pesquisa) {

            $cargo = DB::table('cargo as c')
                ->leftJoin('tp_cargo as tp', 'tp.idTpCargo', '=', 'c.tp_cargo')
                ->select(
                    'c.id',
                    'c.nome',
                    'c.salario',
                    'c.dt_inicio',
                    'tp.nomeTpCargo',
                    'tp.idTpCargo',
                    'c.dt_fim'
                    // 'c.status'
                ) //faz uma pesquisa no banco apenas onde os valores batem
                ->where('c.nome', 'ilike', '%' . $pesquisa . '%')
                ->orWhere('tp.nomeTpCargo', 'ilike', '%' . $pesquisa . '%')
                // ->orderBy('c.status', 'desc')
                ->orderBy('c.nome', 'asc')
                ->get();
        } else {
            //Envia todos os dados do banco para a tabela
            $cargo = DB::table('cargo as c')
                ->leftJoin('tp_cargo as tp', 'tp.idTpCargo', '=', 'c.tp_cargo')
                ->select(
                    'c.id',
                    'c.nome',
                    'c.salario',
                    'c.dt_inicio',
                    'tp.nomeTpCargo',
                    'tp.idTpCargo',
                    'c.dt_fim'
                    // 'c.status'
                )
                // ->orderBy('c.status', 'desc')
                ->orderBy('c.nome', 'asc')
                ->get();
        }

        return view('cargos.gerenciar-cargos', compact('cargo', 'pesquisa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposCargo = DB::table('tp_cargo')->get(); //envia todos os dados da tabela para o incluir

        return view('cargos.incluir-cargos', compact('tiposCargo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input_tipo_cargo = $request->input('tipoCargo');
        $input_nome = $request->input('nome');
        $input_salario = $request->input('salario');

        $dataDeHoje = Carbon::today()->toDateString(); //busca a data de hoje
        $salario = str_replace(',', '.', $input_salario); //substitui a virgula por ponto


        $salario_formatado = preg_replace('/\.(?=.*\.)/', '', $salario);
        // dd($salario_formatado)

        $idcargo = DB::table('cargo')->insertGetId([ //insere e pega o ID, armazenando na variavel
            'nome' => $input_nome,
            'salario' =>  $salario_formatado,
            'dt_inicio' => $dataDeHoje,
            'tp_cargo' => $input_tipo_cargo,
            // 'status' => true,
        ]);

        // $cargo = DB::table('cargo as c') // da select no item recem inserido
        //     ->select('c.nome')
        //     ->where('id', $idcargo)
        //     ->first();

        // dd($dataDeHoje)

        DB::table('hist_cargo')->insert([ //insere uma entrada no historico marcando o id inserido
            'salario' => $salario_formatado ?? null,
            'data_inicio' => $dataDeHoje,
            'id_cargo' => $idcargo,
            'motivo_alt' => 'Primeira Criação do Cargo',
        ]);
        app('flasher')->addSuccess("Cargo $input_nome adicionado com sucesso");
        return redirect()->route('gerenciar.cargos');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cargo = DB::table('cargo') // traz nome e o Id para a funcao visualizar
            ->where('id', $id)
            ->select('id as idCR', 'nome as nomeCR')
            ->first();

        $hist_cargo_regular = DB::table('hist_cargo') //gera todos os dados para a tabela
            ->select('id as idHist', 'salario as salarioHist', 'data_inicio', 'data_fim', 'motivo_alt')
            ->where('id_cargo', '=', $id)
            ->orderBy('id', 'desc')
            ->get();

        return view('cargos.visualizar-cargos', compact('cargo', 'hist_cargo_regular'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $cargo = DB::table('cargo as c')
            ->where('c.id', $id)
            ->first();

        $tiposCargo = DB::table('tp_cargo')->get();


        return view('/cargos/editar-cargos', compact('cargo', 'tiposCargo', 'id'));
    }


    public function destroy(string $id)
    {
        $dataDeHoje = Carbon::today()->toDateString();
        $ultimaModificacao = DB::table('hist_cargo') //pega o dado nao modificado da tabela historico
            ->where('id_cargo', '=', $id)
            ->where('data_fim', null)
            ->first();
        if ($ultimaModificacao) {
        }
        DB::table('hist_cargo') // atualiza a data final desse dado para a data de hoje na tabela historico
            ->where('id', $ultimaModificacao->id)
            ->update([
                'data_fim' => $dataDeHoje,
            ]);

        DB::table('hist_cargo')->insert([ // Insere uma nova linha na tabela historico indicando o fim do dado
            'salario' => $ultimaModificacao->salario,
            'data_inicio' => $dataDeHoje,
            'id_cargo' => $ultimaModificacao->id_cargo,
            'motivoAlt' => 'Fim do Cargo',
        ]);

        $cargofechado = DB::table('cargo') // pega o nome do cargo finalizado
            ->where('id', $ultimaModificacao->id_cargo)
            ->first();
        DB::table('cargos') // atualiza a data de inicio na tabela cargos e coloca o status para desativado
            ->where('id', $ultimaModificacao->id_cargo)
            ->update([
                'dt_inicio' => $dataDeHoje,
                // 'status' => false,
            ]);

        app('flasher')->addSuccess("O cargo $cargofechado->nome foi desativado");
        return redirect()->route('gerenciar.cargos');
    }

    /**
     * Remove the specified resource from storage.
     */
    //desativa o item da tabela
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cargo = DB::table('cargos as c') //traz os dados do Id a ser editado
            ->where('c.id', $id)
            ->first();

        $input = $request->all();
        $dataDeHoje = Carbon::today()->toDateString();
        $dataDeOntem = Carbon::yesterday()->toDateString();
        $salario = str_replace(',', '.', $input['salario']);
        $salario = preg_replace('/\.(?=.*\.)/', '', $salario);

        $ultimaModificacao = DB::table('hist_cargo') //Busca na tabela historico um dado nao editado
            ->where('idcargo', '=', $id)
            ->where('data_fim', null)
            ->first();

        DB::table('hist_cargo')  // Adiciona no dado nao editado uma data final na historico para a data de ontem
            ->where('id', $ultimaModificacao->id)
            ->update([
                'data_fim' => $dataDeOntem,
            ]);

        DB::table('cargos') //Atualiza todos os dados da tabela cargos
            ->where('id', $id)
            ->update([
                'nome' => $input['nome'],
                'salario' => $salario,
                'dt_inicio' => $dataDeHoje,
                'tp_cargo' => $input['tipocargo'],
            ]);

        DB::table('hist_cargo')->insert([ // Insere uma nova linha na tabela historico
            'salario' => $salario,
            'data_inicio' => $dataDeHoje,
            'idcargo' => $id,
            'motivoAlt' => $input['motivo'],
        ]);

        $cargo = DB::table('cargos as c') // Pega o nome do cargo atualizado
            ->where('c.id', $id)
            ->first();
        app('flasher')->addSuccess("O Cargo $cargo->nome foi atualizado");
        return redirect()->route('gerenciar.cargos');
    }
}
