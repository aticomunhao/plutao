<?php

namespace App\Http\Controllers;


use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\CollectionorderBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use iluminate\Support\Facades\Route;


class GerenciarDadosBancariosAssociadoController extends Controller
{
    public function index(Request $request, $id)
    {
        $associado = DB::table('associado AS as')
            ->leftJoin('contribuicao_associado AS cont', 'as.id', '=', 'cont.id_associado')
            ->leftJoin('forma_contribuicao_autorizacao AS contaut', 'cont.id_contribuicao_autorizacao', '=', 'contaut.id')
            ->leftJoin('forma_contribuicao_boleto AS contbolt', 'cont.id_contribuicao_boleto', '=', 'contbolt.id')
            ->leftJoin('forma_contribuicao_tesouraria AS contteso', 'cont.id_contribuicao_tesouraria', '=', 'contteso.id')
            ->leftJoin('pessoas AS p', 'as.id_pessoa', '=', 'p.id')
            ->where('as.id', $id)
            ->select(
                'as.id AS ida',
                'as.nr_associado',
                'p.nome_completo',
                'cont.valor',
                'cont.dt_vencimento',
                'cont.ultima_contribuicao',
                'contaut.banco_do_brasil',
                'contaut.brb',
                'contbolt.mensal',
                'contbolt.trimestral',
                'contbolt.semestral',
                'contbolt.anual',
                'contteso.dinheiro',
                'contteso.cheque',
                'contteso.ct_de_debito',
                'contteso.ct_de_credito'
            )
            ->first();

        //    dd($associado);
        $ida = $request->ida;
        $nome_completo = $request->nome_completo;
        $valor = $request->valor;
        $dt_vencimento = $request->dt_vencimento;
        $ultima_contribuicao = $request->ultima_contribuicao;

        $banco_do_brasil = $request->banco_do_brasil;
        $brb = $request->brb;

        $mensal = $request->mensal;
        $trimestral = $request->trimestral;
        $semestral = $request->semestral;
        $anual = $request->anual;

        $dinheiro = $request->dinheiro;
        $cheque = $request->cheque;
        $ct_de_debito = $request->ct_de_debito;
        $ct_de_credito = $request->ct_de_credito;


        if ($associado->mensal !== null && $associado->mensal == TRUE) {
            $mes = "Mensal";
        } else if ($associado->trimestral !== null && $associado->trimestral == TRUE) {
            $mes = "Trimestral";
        } else if ($associado->semestral !== null && $associado->semestral == TRUE) {
            $mes = "Semestral";
        } else if ($associado->anual !== null && $associado->anual == TRUE) {
            $mes = "Anual";
        } else {
            $mes = "Nenhum";
        }

        //dd($mes);


        if ($associado->banco_do_brasil !== null && $associado->banco_do_brasil == TRUE) {
            $banco = "Banco do Brasil";
        } else if ($associado->brb !== null && $associado->brb == TRUE) {
            $banco = "BRB";
        } else {
            $banco = "Nenhum";
        }


        if ($associado->dinheiro !== null && $associado->dinheiro == TRUE) {
            $tesouraria = "dinheiro";
        } else if ($associado->ct_de_debito !== null && $associado->ct_de_debito == TRUE) {
            $tesouraria = "Cartão de Débito";
        } else if ($associado->ct_de_credito !== null && $associado->ct_de_credito == TRUE) {
            $tesouraria = "Cartão de Crédito";
        } else if ($associado->cheque !== null && $associado->cheque == TRUE) {
            $tesouraria = "Cheque";
        } else {
            $tesouraria = "Nenhum";
        }


        // dd($associado);
        return view('associado/gerenciar-dados-bancarios-associado', compact('associado', 'banco', 'mes', 'tesouraria', 'dt_vencimento', 'ida'));
    }

    public function visualizardadosbancarios(Request $request, $id)
    {

        $associado = DB::table('associado AS as')
            ->leftJoin('contribuicao_associado AS cont', 'as.id', '=', 'cont.id_associado')
            ->leftJoin('forma_contribuicao_autorizacao AS contaut', 'cont.id_contribuicao_autorizacao', '=', 'contaut.id')
            ->leftJoin('forma_contribuicao_boleto AS contbolt', 'cont.id_contribuicao_boleto', '=', 'contbolt.id')
            ->leftJoin('forma_contribuicao_tesouraria AS contteso', 'cont.id_contribuicao_autorizacao', '=', 'contteso.id')
            ->leftJoin('pessoas AS p', 'as.id_pessoa', '=', 'p.id') // Corrigido o operador de comparação aqui
            ->where('as.id', $id)
            ->select(
                'p.nome_completo',
                'cont.valor',
                'cont.dt_vencimento',
                'cont.ultima_contribuicao',
                'contaut.banco_do_brasil',
                'contaut.brb',
                'contbolt.mensal',
                'contbolt.trimestral',
                'contbolt.semestral',
                'contbolt.anual',
                'contteso.dinheiro',
                'contteso.cheque',
                'contteso.ct_de_debito',
                'contteso.ct_de_credito',
            )
            ->get();

        ///    dd($associado);

        //$nm_completo = $request->nome_completo;


        return view('associado/gerenciar-dados-bancarios-associado', compact('associado'));
    }

    public function store($ida)
    {
        // $validacao = DB::table('contribuicao_associado AS ca')->where('id_associado', $ida)
        // ->select('ca.id AS idca');


        //  dd($validacao);
        //  if ($validacao->select('idca') == []) {

        //  app('flasher')->addSuccess('Associado Já possui cadastro bancario!');

        //$associado = DB::table('associado AS as')
        //->select('as.id AS ida')
        //->where('as.id', $ida)
        //->get();

        //return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida, 'associado'=>$associado]);
        //} else {

        $comparar_id = DB::table('associado')
            ->leftJoin('contribuicao_associado AS ca', 'associado.id', '=', 'ca.id_associado')
            ->where('associado.id', $ida)
            ->select('ca.id AS id_contribuicao_associado', 'associado.id AS id_associado')
            ->first(); // Use first() para obter apenas o primeiro resultado

        if ($comparar_id->id_contribuicao_associado !== null) {
            app('flasher')->addError('Associado já possui Dados Bancários!');
            return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
        } else {

            $associado = DB::table('associado AS as')
                ->select('as.id AS ida')
                ->where('as.id', $ida)
                ->get();


            $desc_bancos = DB::table('desc_ban')
                ->orderBy('id_db')
                ->get();


            //  dd($associado);
            return view('associado/incluir-dados_bancarios', compact('associado', 'ida', 'desc_bancos'));
        }
    }

    public function agenciaselect($id)
    {
        $agenciasdoselect = DB::table('tp_banco_ag')
            ->where('banco', $id)
            ->orderBy('agencia')
            ->get();
        return response()->json($agenciasdoselect);
    }

    public function incluirdadosbancarios(Request $request, $ida)
    {

        $dinheiro = $request->has('tesouraria') && $request->tesouraria === 'dinheiro';
        $cheque = $request->has('tesouraria') && $request->tesouraria === 'cheque';
        $ct_de_debito = $request->has('tesouraria') && $request->tesouraria === 'ct_de_debito';
        $ct_de_credito = $request->has('tesouraria') && $request->tesouraria === 'ct_de_credito';

        DB::table('forma_contribuicao_tesouraria')->insert([
            'dinheiro' => $dinheiro,
            'cheque' => $cheque,
            'ct_de_debito' => $ct_de_debito,
            'ct_de_credito' => $ct_de_credito,
        ]);
        dd($request->all());


        $mensal = $request->has('tesouraria') && $request->tesouraria === 'mensal';
        $trimestral = $request->has('tesouraria') && $request->tesouraria === 'trimestral';
        $semestral = $request->has('tesouraria') && $request->tesouraria === 'semestral';
        $anual = $request->has('tesouraria') && $request->tesouraria === 'anual';

        DB::table('forma_contribuicao_boleto')->insert([
            'mensal' => $mensal,
            'trimestral' => $trimestral,
            'semestral' => $semestral,
            'anual' => $anual,
        ]);

        $banco_do_brasil = $request->has('tesouraria') && $request->tesouraria === 'banco_do_brasil';
        $brb = $request->has('tesouraria') && $request->tesouraria === 'brb';

        DB::table('forma_contribuicao_autorizacao')->insert([
            'banco_do_brasil' => $banco_do_brasil,
            'brb' => $brb
        ]);

        $id_cont_tesouraria = DB::table('forma_contribuicao_tesouraria')
            ->select(DB::raw('MAX(id) as max_id'))
            ->value('max_id');

        $id_cont_boleto = DB::table('forma_contribuicao_boleto')
            ->select(DB::raw('MAX(id) as max_id'))
            ->value('max_id');

        $id_cont_boleto = DB::table('forma_contribuicao_autorizacao')
            ->select(DB::raw('MAX(id) as max_id'))
            ->value('max_id');

        $id_contribuicao = DB::table('contribuicao_associado')
            ->select(DB::raw('MAX(id) as max_id'))
            ->value('max_id');

        // dd($id_contribuicao);

        DB::table('contribuicao_associado')
            ->insert([

                'id_banco' => $request->input('desc_banco'),
                'id_agencia' => $request->input('tp_banco_ag'),
                'id_associado' => $ida,
                'id_contribuicao_tesouraria' => $id_cont_tesouraria,
                'id_contribuicao_boleto' => $id_cont_boleto,
                'id_contribuicao_autorizacao' => $id_cont_boleto,
                'valor' => $request->input('valor'),
                'dt_vencimento' => $request->input('dt_vencimento'),
                'nr_cont_corrente' => $request->input('conta_corrente')
            ]);


        //    dd($associado_dados_bancarios);

        //        DB::table('associado AS as')
        // ->where('as.id', $ida)
        //  ->update(['as.id_contribuicao_associado' => $id_contribuicao]);
        //  dd($oi);

        app('flasher')->addSuccess('Cadastro Dados Bancários do Associado foi realizado com sucesso.');

        return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
    }

    public function edit(Request $request, $ida)

    {
        $comparar_id = DB::table('associado')
            ->leftJoin('contribuicao_associado AS ca', 'associado.id', '=', 'ca.id_associado')
            ->where('associado.id', $ida)
            ->select('ca.id AS id_contribuicao_associado', 'associado.id AS id_associado')
            ->first();

        if ($comparar_id->id_contribuicao_associado === null) {
            app('flasher')->addError('Associado não possui Cadastro de Dados Bancários!');
            return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
        } else {
            $dados_bancarios_associado = DB::table('associado AS as')
                ->leftJoin('contribuicao_associado AS cont', 'as.id', '=', 'cont.id_associado')
                ->leftJoin('forma_contribuicao_autorizacao AS contaut', 'cont.id_contribuicao_autorizacao', '=', 'contaut.id')
                ->leftJoin('forma_contribuicao_boleto AS contbolt', 'cont.id_contribuicao_boleto', '=', 'contbolt.id')
                ->leftJoin('forma_contribuicao_tesouraria AS contteso', 'cont.id_contribuicao_tesouraria', '=', 'contteso.id')
                ->leftJoin('pessoas AS p', 'as.id_pessoa', '=', 'p.id')
                ->where('as.id', $ida)
                ->select(
                    'as.id AS ida',
                    'p.nome_completo',
                    'cont.id_contribuicao_tesouraria AS idt',
                    'cont.id_contribuicao_boleto AS idb',
                    'cont.id_contribuicao_autorizacao AS idc',
                    'cont.valor',
                    'cont.dt_vencimento',
                    'cont.ultima_contribuicao',
                    'cont.nr_cont_corrente',
                    'contaut.banco_do_brasil',
                    'contaut.brb',
                    'cont.id_agencia',
                    'cont.id_banco',
                    'contbolt.mensal',
                    'contbolt.trimestral',
                    'contbolt.semestral',
                    'contbolt.anual',
                    'contteso.dinheiro',
                    'contteso.cheque',
                    'contteso.ct_de_debito',
                    'contteso.ct_de_credito'
                )
                ->get();


            $id_agencia = $dados_bancarios_associado->first()->id_agencia;
            $id_banco = $dados_bancarios_associado->first()->id_banco;


            $tpagencia = DB::table('tp_banco_ag as tb')
                ->where('id', $id_agencia)
                ->select(
                    'tb.desc_agen AS descricao',
                    'tb.id AS id_agencia'
                )
                ->first();

            $tpbanco = DB::table('desc_ban AS db')
                ->where('id_db', $id_banco)
                ->select(
                    'db.id_db AS id_banco',
                    'db.nome AS banco'
                )
                ->first();

            $desc_bancos = DB::table('desc_ban')
                ->orderBy('id_db')
                ->get();


            return view('associado/editar-dados-bancarios-associado', compact('tpagencia', 'tpbanco', 'dados_bancarios_associado', 'desc_bancos'));
        }
    }

    public function documentobancariopdf($id)
    {
        $comparar_id = DB::table('associado')
            ->leftJoin('contribuicao_associado AS ca', 'associado.id', '=', 'ca.id_associado')
            ->where('associado.id', $id)
            ->select('ca.id AS id_contribuicao_associado', 'associado.id AS id_associado')
            ->first();

        if ($comparar_id->id_contribuicao_associado === null) {
            app('flasher')->addError('Associado não possui Cadastro de Dados Bancários!');
            return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $id]);
        } else {
            $associado = DB::table('associado AS as')
                ->leftJoin('contribuicao_associado AS cont', 'as.id', '=', 'cont.id_associado')
                ->leftJoin('forma_contribuicao_autorizacao AS contaut', 'cont.id_contribuicao_autorizacao', '=', 'contaut.id')
                ->leftJoin('pessoas AS p', 'as.id_pessoa', '=', 'p.id')
                ->leftJoin('endereco_pessoas AS endp', 'p.id', '=', 'endp.id_pessoa')
                ->leftjoin('tp_cidade AS tc', 'endp.id_cidade', '=', 'tc.id_cidade')
                ->where('as.id', $id)
                ->select(
                    'as.nr_associado',
                    'p.nome_completo',
                    'p.cpf',
                    'p.idt',
                    'p.celular',
                    'p.email',
                    'endp.cep',
                    'endp.logradouro',
                    'endp.numero',
                    'endp.bairro',
                    'endp.complemento',
                    'tc.descricao',
                    'cont.valor',
                    'cont.nr_cont_corrente',
                    'contaut.banco_do_brasil',
                    'contaut.brb',
                    'cont.dt_vencimento',
                    'cont.id_agencia',
                    'cont.id_banco'

                )
                ->first();

            $id_agencia = $associado->id_agencia;

            $tpagencia = DB::table('tp_banco_ag as tb')
                ->where('id', $id_agencia)
                ->select(
                    'tb.agencia AS agencia',
                    'tb.id AS id_agencia'
                )
                ->first();


            //dd($associado);

            $html = View::make('associado/documento-bancario', compact('associado', 'tpagencia'))->render();

            // Cria uma instância do Dompdf
            $dompdf = new Dompdf();

            // Carrega o HTML no Dompdf
            $dompdf->loadHtml($html);

            // Renderiza o PDF
            $dompdf->render();

            // Saída do PDF no navegador
            return $dompdf->stream();
        }
    }

    public function salvardocumentobancario(Request $request, $ida)
    {

        if ($request->hasFile('arquivo')) {
            $arquivo = $request->file('arquivo');

            $nomeArquivo = Hash::make($arquivo->getClientOriginalName());

            // dd($arquivo);

            $caminhoArquivo = $arquivo->storeAs('public/documentos-bancarios-associado', $nomeArquivo);

            DB::table('contribuicao_associado')
                ->where('id_associado', $ida)
                ->update(['caminho_documento_bancario' => $caminhoArquivo]);

            app('flasher')->addSuccess('Documento Armazenado!');
            return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
        }
    }

    public function update(Request $request, $ida, $idc, $idt, $idb)
    {
        DB::table('contribuicao_associado')
            ->where('id_associado', $ida)
            ->update([
                'valor' => $request->input('valor'),
                'dt_vencimento' => $request->input('dt_vencimento'),
                'id_banco' => $request->input('desc_banco'),
                'id_agencia' => $request->input('tp_banco_ag'),
                'nr_cont_corrente' => $request->input('conta_corrente')

            ]);
        //   dd($request->input('tp_banco_ag'));

        $dinheiro = $request->input('tesouraria') === 'dinheiro';
        $cheque = $request->input('tesouraria') === 'cheque';
        $ct_de_debito = $request->input('tesouraria') === 'ct_de_debito';
        $ct_de_credito = $request->input('tesouraria') === 'ct_de_credito';


        DB::table('forma_contribuicao_tesouraria AS fct')
            ->where('fct.id', $idc)
            ->update([
                'dinheiro' => $dinheiro,
                'cheque' => $cheque,
                'ct_de_debito' => $ct_de_debito,
                'ct_de_credito' => $ct_de_credito,
            ]);


        $mensal = $request->has('tesouraria') && $request->tesouraria === 'mensal';
        $trimestral = $request->has('tesouraria') && $request->tesouraria === 'trimestral';
        $semestral = $request->has('tesouraria') && $request->tesouraria === 'semestral';
        $anual = $request->has('tesouraria') && $request->tesouraria === 'anual';

        DB::table('forma_contribuicao_boleto AS fcb')
            ->where('fcb.id', $idb)
            ->update([
                'mensal' => $mensal,
                'trimestral' => $trimestral,
                'semestral' => $semestral,
                'anual' => $anual,
            ]);


        $banco_do_brasil = $request->input('tesouraria') === 'banco_do_brasil';
        $brb = $request->input('tesouraria') === 'brb';

        DB::table('forma_contribuicao_autorizacao AS fca')
            ->where('fca.id', $idt)
            ->update([
                'banco_do_brasil' => $banco_do_brasil,
                'brb' => $brb
            ]);


        return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
    }

    public function visualizardocumentobancario($ida)
    {
        $caminhodocumento = DB::table('contribuicao_associado AS ca')
            ->where('id_associado', $ida)
            ->select(['ca.caminho_documento_bancario'])
            ->first();

        //dd($caminhodocumento);

        if ($caminhodocumento) {
            $caminho = $caminhodocumento->caminho_documento_bancario;


            if (Storage::exists($caminho)) {
                return response()->file(storage_path('app/' . $caminho));
            } else {
                return abort(404);
            }
        } else {

            return abort(404);
        }
    }

    public function delete($ida)
    {
        $dados_banc_associado = DB::table('contribuicao_associado AS ca')
            ->where('id_associado', $ida)
            ->select(
                'ca.id_contribuicao_tesouraria',
                'ca.id_contribuicao_boleto',
                'ca.id_contribuicao_autorizacao'
            )->get();

        //   dd($deletar_dados_banc_associado);


        $id_contribuicao_tesouraria = $dados_banc_associado->first()->id_contribuicao_tesouraria;
        $id_contribuicao_boleto = $dados_banc_associado->first()->id_contribuicao_boleto;
        $id_contribuicao_autorizacao = $dados_banc_associado->first()->id_contribuicao_autorizacao;

        // $deletar_cont_tesouraria = DB::table('forma_contribuicao_tesouraria')
        // ->where('id', $id_contribuicao_tesouraria)
        //->delete();

        //$deletar_cont_boleto = DB::table('forma_contribuicao_boleto')     //->where('id', $id_contribuicao_boleto)
        //->delete();

        //$deletar_cont_autorizacao = DB::table('forma_contribuicao_autorizacao')
        // ->where('id', $id_contribuicao_autorizacao)
        //  ->delete();

        $deletar_dados_banc_associado = DB::table('contribuicao_associado')
            ->where('id_associado', $ida)
            ->delete();


        app('flasher')->addSuccess('O dados bancarios do associado foi Removido com Sucesso.');

        return redirect()->route('gerenciar-dados-bancario-associado', ['id' => $ida]);
    }
}
