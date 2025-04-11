<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use Dompdf\Dompdf;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\CollectionorderBy;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use iluminate\Support\Facades\Route;

class GerenciarAssociadoController extends Controller
{
    public function index(Request $request)
    {


        $lista_associado = DB::table('associado AS ass')
            ->leftJoin('membro AS m', 'ass.nr_associado', 'm.id_associado')
            ->leftJoin('pessoas AS p', 'p.id', 'ass.id_pessoa')
            ->leftJoin('historico_associado as ha', 'ass.id', 'ha.id_associado')
            ->select(
                DB::raw('CASE WHEN ha.dt_fim IS NULL THEN \'Ativo\' ELSE \'Inativo\' END AS status'),
                'ass.nr_associado',
                'ass.id as ida',
                'p.nome_completo',
                'ha.dt_inicio',
                'ha.dt_fim',
                'ass.caminho_foto_associado',
                DB::raw('(CASE WHEN EXISTS (
                        SELECT m.id_associado FROM membro AS m
                        WHERE m.id_associado = ass.nr_associado
                        AND m.dt_fim IS NULL
                    ) THEN \'Sim\' ELSE \'Não\' END) AS voluntario')
            )
            ->distinct();

        $id = $request->id;
        $nr_associado = $request->nr_associado;
        $nome_completo = $request->nome_completo;
        $voluntario = $request->voluntario;
        $votante = $request->votante;
        $dt_inicio = $request->dt_inicio;
        $dt_fim = $request->dt_fim;
        $status = $request->status;



        // dd($lista_associado);
        if ($request->nr_associado) {
            $lista_associado->where('ass.nr_associado', 'ilike', "%$nr_associado%");
        }


        if ($request->nome_completo) {
            $lista_associado->whereRaw("UNACCENT(LOWER(p.nome_completo)) ILIKE UNACCENT(LOWER(?))", ["%{$request->nome_completo}%"]);
        }

        if ($request->dt_inicio) {
            $lista_associado->where('ha.dt_inicio', '=', $request->dt_inicio);
        }


        if ($request->dt_fim) {
            $lista_associado->where('ha.dt_fim', '=', $request->dt_fim);
        }


        if ($request->status == 1) {
            $lista_associado->where('ha.dt_fim', null);
        } elseif ($request->status == 2) {
            $lista_associado->whereNotNull('ha.dt_fim');
        }


        $lista_associado = $lista_associado->orderBy('status', 'asc', 'voluntario', 'asc')->orderBy('ass.nr_associado', 'asc')->paginate(100);

        $motivo = DB::table('tipo_motivo_status_pessoa')
                    ->select('id as idsp', 'motivo')->orderBy('motivo', 'asc')->get();


        return view('/associado/gerenciar-associado', compact('lista_associado', 'id', 'nr_associado', 'nome_completo', 'voluntario', 'votante', 'dt_inicio', 'dt_fim', 'status', 'motivo'));
    }

    public function create(Request $request)
    {

        $cidade = DB::select('select id_cidade, descricao from tp_cidade');

        $tp_uf = DB::select('select id, sigla from tp_uf');

        $ddd = DB::select('select id, descricao, uf_ddd from tp_ddd');

        $sexo = DB::select('select id, tipo from tp_sexo');


        return view('/associado/incluir-associado', compact('cidade', 'tp_uf', 'ddd', 'sexo'));
    }

    public function retornaCidadeDadosResidenciais($id)
    {
        $cidadeDadosResidenciais = DB::table('tp_cidade')
            ->where('id_uf', $id)
            ->get();

        return response()->json($cidadeDadosResidenciais);
    }

    public function store(Request $request)
    {
        $cpf = $request->input('cpf');

        $associado = DB::table('pessoas as p')
        ->leftJoin('associado as a', 'p.id', 'a.id_pessoa')
        ->where('p.cpf', $cpf)
        ->whereNotNull('a.id')->count();

        $pessoa = DB::table('pessoas')->where('cpf', $cpf)->count();
        
        $endereco = DB::table('endereco_pessoas as ep')
        ->leftJoin('pessoas as p', 'ep.id_pessoa', 'p.id')
        ->where('p.cpf', $cpf)
        ->count();

      
        try {
            $validated = $request->validate([
                //'telefone' => 'required|telefone',
                'cpf' => 'required|cpf',
                //'cnpj' => 'required|cnpj',
                // outras validações aqui
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            app('flasher')->addError('Este CPF não é válido');

            return redirect()->back()->withErrors($e->errors())->withInput();
        }   
        //dd($pessoa == 0);
        //foreach ($comparar_cpf as $cpf) {
        if ($associado > 0) 
        {
            app('flasher')->adderror('Esta pessoa já é associada!');
            return redirect('/gerenciar-associado');

        }elseif($pessoa == 0)
        {
            DB::table('pessoas')
                ->insert([
                    'nome_completo' => $request->input('nome_completo'),
                    'cpf' => $request->input('cpf'),
                    'ddd' => $request->input('ddd'),
                    'celular' => $request->input('telefone'),
                    'email' => $request->input('email'),
                    'idt' => $request->input('idt'),
                    'sexo' => $request->input('sexo'),
                    'dt_nascimento' => $request->input('dt_nascimento'),
                    'status' => '1',
                ]);

                $id_pessoa = DB::table('pessoas')
                ->max('id');

            DB::table('associado')
                ->insert([
                    'id_pessoa' => $id_pessoa,
                    'nr_associado' => $request->input('nrassociado'),
                ]);

                $id_associado = DB::table('associado')
                ->max('id');

            DB::table('historico_associado')
            ->insert([
                'id_associado' => $id_associado,
                'dt_inicio' => $request->input('dt_inicio'),
            ]);

            DB::table('endereco_pessoas')->insert([
                'id_pessoa' => $id_pessoa,
                'cep' => str_replace('-', '', $request->input('cep')),
                'dt_inicio' => $request->input('dt_inicio'),
                'id_uf_end' => $request->input('uf_end'),
                'id_cidade' => $request->input('cidade'),
                'logradouro' => $request->input('logradouro'),
                'numero' => $request->input('numero'),
                'bairro' => $request->input('bairro'),
                'complemento' => $request->input('complemento'),
            ]);

            app('flasher')->addSuccess('O cadastro do Associado foi realizado com sucesso.');

            return redirect('/gerenciar-associado');

        }elseif($pessoa > 0 && $associado == 0 && $endereco == 0)
        {
            DB::table('pessoas')
                ->where('cpf', $cpf)
                ->update([
                    'ddd' => $request->input('ddd'),
                    'celular' => $request->input('telefone'),
                    'email' => $request->input('email'),
                    'idt' => $request->input('idt'),
                    'sexo' => $request->input('sexo'),
                    'dt_nascimento' => $request->input('dt_nascimento'),
                    'status' => '1',
                ]);

            $id_pessoa = DB::table('pessoas')
                ->where('pessoas.cpf', $cpf)
                ->value('id');    

            DB::table('associado')
                ->insert([
                    'id_pessoa' => $id_pessoa,
                    'nr_associado' => $request->input('nrassociado'),

                ]);

                $id_associado = DB::table('associado')
                ->max('id');

            DB::table('historico_associado')
            ->insert([
                'id_associado' => $id_associado,
                'dt_inicio' => $request->input('dt_inicio'),
            ]);

            DB::table('endereco_pessoas')
                ->insert([
                'id_pessoa' => $id_pessoa,
                'dt_inicio' => $request->input('dt_inicio'),
                'cep' => str_replace('-', '', $request->input('cep')),
                'id_uf_end' => $request->input('uf_end'),
                'id_cidade' => $request->input('cidade'),
                'logradouro' => $request->input('logradouro'),
                'numero' => $request->input('numero'),
                'bairro' => $request->input('bairro'),
                'complemento' => $request->input('complemento'),
            ]);

            app('flasher')->addSuccess('O cadastro do associado e seu endereço foi concluído e foi atualizado o cadastro de pessoa.');

            return redirect('/gerenciar-associado');

        }elseif($pessoa > 0 && $associado == 0 && $endereco > 0)
        {
            DB::table('pessoas')
                ->where('cpf', $cpf)
                ->update([
                    'ddd' => $request->input('ddd'),
                    'celular' => $request->input('telefone'),
                    'email' => $request->input('email'),
                    'idt' => $request->input('idt'),
                    'sexo' => $request->input('sexo'),
                    'dt_nascimento' => $request->input('dt_nascimento'),
                    'status' => '1',
                ]);

            $id_pessoa = DB::table('pessoas')
                ->where('pessoas.cpf', $cpf)
                ->value('id');    

            DB::table('associado')
                ->insert([
                    'id_pessoa' => $id_pessoa,
                    'nr_associado' => $request->input('nrassociado'),

                ]);

                $id_associado = DB::table('associado')
                ->max('id');

            DB::table('historico_associado')
            ->insert([
                'id_associado' => $id_associado,
                'dt_inicio' => $request->input('dt_inicio'),
            ]);



            app('flasher')->addSuccess('O cadastro de Associado foi concluído e atualizado o cadastro de pessoa.');

            return redirect('/gerenciar-associado');
        
        }

        app('flasher')->addError('Ocorreu um erro inesperado, avise a ATI.');

        return redirect()->back()->withInput();


    }

    public function edit($id)
    {
        $edit_associado = DB::table('associado AS ass')
        ->leftJoin('pessoas AS p', 'ass.id_pessoa', '=', 'p.id')
        ->leftJoin('tp_sexo', 'p.sexo', 'tp_sexo.id')
        ->leftJoin('endereco_pessoas AS endp', function ($join) {
            $join->on('p.id', '=', 'endp.id_pessoa')
                 ->whereRaw('endp.id = (SELECT MAX(id) FROM endereco_pessoas WHERE id_pessoa = p.id)');
        })
        ->leftJoin('tp_uf', 'endp.id_uf_end', '=', 'tp_uf.id')
        ->leftJoin('tp_ddd', 'tp_ddd.id', '=', 'p.ddd')
        ->leftJoin('tp_cidade AS tc', 'endp.id_cidade', '=', 'tc.id_cidade')
        ->leftJoin('historico_associado as ha', 'ass.id', 'ha.id_associado')
        ->where('ass.id', $id)
        ->select(
            'ass.id AS ida',
            'ass.nr_associado',
            'p.id AS idp',
            'endp.id AS ide',
            'p.nome_completo',
            'p.cpf',
            'p.celular',
            'p.email',
            'p.idt',
            'p.sexo AS id_sexo',
            'p.dt_nascimento AS dt_nascimento',
            'tp_sexo.tipo AS nome_sexo',
            'tp_ddd.id AS tpd',
            'tp_ddd.descricao AS dddesc',
            'tp_uf.id AS tuf',
            'tp_uf.sigla AS ufsgl',
            'endp.cep',
            'tc.descricao',
            'endp.logradouro',
            'endp.numero',
            'endp.bairro',
            'endp.complemento',
            'tc.id_cidade',
            'tc.descricao AS nat',
            'nr_associado AS nrAssociado'
        )
        ->get();

        $tpddd = DB::table('tp_ddd')->select('id', 'descricao')->get();
        $tpsexo = DB::table('tp_sexo')->select('id', 'tipo')->get();
        $tpcidade = DB::table('tp_cidade')->select('id_cidade', 'descricao')->get();
        $tpufidt = DB::table('tp_uf')->select('id', 'sigla')->get();

        $tp_uf = DB::select('select id, sigla from tp_uf');


        //dd($tpcidade);

        //dd($edit_associado);

        return view('associado/editar-associado', compact('edit_associado', 'tpddd', 'tpcidade', 'tpufidt', 'tp_uf', 'tpsexo'));
    }

    public function documentobancariopdf($id)
    {


        $associado = DB::table('associado AS as')
            ->leftJoin('contribuicao_associado AS cont', 'as.id', '=', 'cont.id_associado')
            ->leftJoin('forma_contribuicao_autorizacao AS contaut', 'cont.id_contribuicao_autorizacao', '=', 'contaut.id')
            ->leftJoin('forma_contribuicao_boleto AS contbol', 'cont.id_contribuicao_boleto', '=', 'contbol.id')
            ->leftJoin('forma_contribuicao_tesouraria AS conttes', 'cont.id_contribuicao_tesouraria', '=', 'conttes.id')
            ->leftJoin('pessoas AS p', 'as.id_pessoa', '=', 'p.id')
            ->leftJoin('endereco_pessoas AS endp', 'p.id', '=', 'endp.id_pessoa')
            ->leftJoin('tp_uf AS tuf', 'endp.id_uf_end', '=', 'tuf.id')
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
                'contaut.banco_do_brasil',
                'contaut.brb',
                'cont.dt_vencimento',
                'cont.id_agencia',
                'cont.id_banco',
                'contbol.mensal',
                'contbol.trimestral',
                'contbol.semestral',
                'contbol.anual',
                'conttes.dinheiro',
                'conttes.cheque',
                'conttes.ct_de_debito',
                'conttes.ct_de_credito',
                'tuf.sigla',
                'as.caminho_foto_associado'

            )
            ->first();


        $html = View::make('associado/documento-associado', compact('associado'))->render();

        // Cria uma instância do Dompdf
        $dompdf = new Dompdf();


        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);

        // Renderiza o PDF
        $dompdf->render();

        // Saída do PDF no navegador
        return $dompdf->stream();
    }

    public function salvardocumento(Request $request, $id)
    {
        try {
            if ($request->hasFile('arquivo')) {
                $arquivo = $request->file('arquivo');

                $hashcode = str_replace(['+', '/', '='], '', base64_encode(random_bytes(10)));
                // Gera um nome único para o arquivo usando o nome original e a data e hora atual
                $nomeArquivo = Carbon::now()->format('YmdHisu') . $hashcode;

                // Armazena o arquivo na pasta especificada
                $caminhoArquivo = $arquivo->storeAs('public/documentos-associado', $nomeArquivo);

                // Atualiza o caminho do documento na base de dados
                DB::table('associado')
                    ->where('id', $id)
                    ->update(['caminho_documento' => $caminhoArquivo]);

                // Adiciona uma mensagem de sucesso
                app('flasher')->addSuccess('Documento Armazenado!');

                return redirect('/gerenciar-associado');
            } else {
                app('flasher')->addSuccess('Nenhum dado Foi Inserido!');

                return redirect('/gerenciar-associado');
            }
        } catch (\Exception $exception) {
            return redirect('/gerenciar-associado');
        }
    }

    public function update(Request $request, $ida, $idp)
    {

        $idEndereco = DB::table('endereco_pessoas')
        ->where('id_pessoa', $idp)
        ->orderByDesc('id') // Pegando o endereço mais recente
        ->value('id'); // Retorna o maior ID

        $endereco = DB::table('endereco_pessoas as ep')
        ->leftJoin('pessoas as p', 'ep.id_pessoa', 'p.id')
        ->where('p.id', $idp)
        ->count();

        $associado = DB::table('pessoas as p')
        ->leftJoin('associado as a', 'p.id', 'a.id_pessoa')
        ->where('p.id', $idp)
        ->whereNotNull('a.id')->count();

        $pessoa = DB::table('pessoas as p')->where('p.id', $idp)->count();
        
        //dd($associado, $pessoa);

        try {
            $validated = $request->validate([
                //'telefone' => 'required|telefone',
                'cpf' => 'required|cpf',
                //'cnpj' => 'required|cnpj',
                // outras validações aqui
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {

            app('flasher')->addError('Este CPF não é válido');
            return redirect()->back()->withErrors($e->errors())->withInput();
        }   

        $cpfExiste = DB::table('pessoas')
            ->where('cpf', $request->cpf)
            ->where('id', '!=', $idp) // Exclui o próprio usuário
            ->exists();

        if($cpfExiste){
            app('flasher')->addError('O CPF digitado está em uso.');
            return redirect()->back()->withInput();

        }elseif($pessoa == 1 && $associado == 1 && $endereco == 0)
        {
            DB::table('pessoas as p')
                ->where('p.id', $idp)
                ->update([
                    'ddd' => $request->input('ddd'),
                    'celular' => $request->input('telefone'),
                    'email' => $request->input('email'),
                    'idt' => $request->input('idt'),
                    'sexo' => $request->input('sexo'),
                    'dt_nascimento' => $request->input('dt_nascimento'),
                    'status' => '1',
                ]);   

            DB::table('associado as a')
                ->where('a.id', $ida)
                ->update([
                    'nr_associado' => $request->input('nrassociado'),

                ]);

            DB::table('endereco_pessoas as ep')
                ->insert([
                'id_pessoa' => $idp,
                'cep' => str_replace('-', '', $request->input('cep')),
                'dt_inicio' =>  Carbon::now()->toDateString(),
                'id_uf_end' => $request->input('uf_end'),
                'id_cidade' => $request->input('cidade'),
                'logradouro' => $request->input('logradouro'),
                'numero' => $request->input('numero'),
                'bairro' => $request->input('bairro'),
                'complemento' => $request->input('complemento'),
            ]);

            app('flasher')->addSuccess('Foi incluido o endereço e atualizados os dados do associado!');

            return redirect('/gerenciar-associado');


        }elseif($pessoa == 1 && $associado == 1 && $endereco > 0)
        {

            DB::table('pessoas as p')
                ->where('p.id', $idp)
                ->update([
                    'nome_completo' => $request->input('nome_completo'),
                    'cpf' => $request->input('cpf'),
                    'ddd' => $request->input('ddd'),
                    'celular' => $request->input('telefone'),
                    'email' => $request->input('email'),
                    'email' => $request->input('email'),
                    'sexo' => $request->input('sexo'),
                    'dt_nascimento' => $request->input('dt_nascimento'),
                    'idt' => $request->input('idt')
                ]);

            DB::table('associado as a')
                ->where('a.id', $ida)
                ->update([
                    'nr_associado' => $request->input('nrassociado'),
                ]);

                if ($idEndereco) {
                    DB::table('endereco_pessoas')
                        ->where('id', $idEndereco)
                        ->update([
                            'cep'         => $request->input('cep'),
                            'id_uf_end'   => $request->input('uf_end'),
                            'id_cidade'   => $request->input('cidade'),
                            'logradouro'  => $request->input('logradouro'),
                            'numero'      => $request->input('numero'),
                            'bairro'      => $request->input('bairro'),
                            'complemento' => $request->input('complemento'),
                        ]);
                }

                app('flasher')->addSuccess('Todos os dados do associado foram atualizados!');

                return redirect('/gerenciar-associado');
        
        }

        app('flasher')->addError('Ocorreu um erro inesperado, avise a ATI.');
        return redirect()->back()->withInput();
    }

    public function visualizarDocumento($id)
    {
        $associado = DB::table('associado')
            ->where('id', $id)
            ->select('caminho_documento')
            ->first();

        if (!$associado || !$associado->caminho_documento) {
            app('flasher')->addError('Nenhum arquivo armazenado.');
            return redirect('/gerenciar-associado');
        }

        $caminhoDocumento = $associado->caminho_documento;

        if (Storage::exists($caminhoDocumento)) {
            return response()->file(storage_path('app/' . $caminhoDocumento));
        }

        return abort(404);
    }

    function delete($id)
    {
        $delete_associado = DB::table('associado')->where('associado.id', $id)->delete();

        app('flasher')->addSuccess('O cadastro do Associado foi excluido com sucesso.');

        return redirect('/gerenciar-associado');
    }

    function inativar(Request $request, $ida)
    {

        $verif_hist = DB::table('historico_associado as ha')->where('ha.id_associado', $ida)->whereNull('dt_fim')->count();

        //dd($verif_hist);
    
        if($verif_hist > 1){

            app('flasher')->addError('Existem dois períodos em aberto, contatar ATI.');

            return redirect('/gerenciar-associado');

        }else{

            DB::table('historico_associado as ha')      
            ->where('ha.id_associado', $ida)
            ->whereNull('dt_fim')
            ->update([
                'dt_fim' => $request->input('dt_fim'),
                'id_motivo' => $request->input('motivo'),
            ]);

            app('flasher')->addSuccess('O associado(a) foi inativado!');
            
            return redirect('/gerenciar-associado');
        }

        app('flasher')->addError('Ocorreu um erro inesperado contate a ATI!');

        return redirect('/gerenciar-associado');
    }
}
