<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ControleFeriasController;
use App\Http\Controllers\ControleVagasController;
use App\Http\Controllers\GerenciarContratoController;
use App\Http\Controllers\GerenciarAfastamentosController;
use App\Http\Controllers\GerenciarAssociadoController;
use App\Http\Controllers\GerenciarBaseSalarialController;
use App\Http\Controllers\GerenciarCargos;
use App\Http\Controllers\GerenciarCargosController;
use App\Http\Controllers\GerenciarCertificadosController;
use App\Http\Controllers\GerenciarDadosBancariosAssociadoController;
use App\Http\Controllers\GerenciarDadosBancariosController;
use App\Http\Controllers\GerenciarDataLimiteDeFeriasController;
use App\Http\Controllers\GerenciarDependentesController;
use App\Http\Controllers\GerenciarEfetivoController;
use App\Http\Controllers\GerenciarEntidadesController;
use App\Http\Controllers\GerenciarFeriasController;
use App\Http\Controllers\GerenciarFuncionarioController;
use App\Http\Controllers\GerenciarHierarquiaController;
use App\Http\Controllers\GerenciarPerfil;
use App\Http\Controllers\GerenciarSetor;
use App\Http\Controllers\GerenciarSetoresController;
use App\Http\Controllers\GerenciarTipoDeContratoController;
use App\Http\Controllers\GerenciarTipoDescontoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*Gerenciar login*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::any('/', [App\Http\Controllers\LoginController::class, 'index']);
Route::any('/login/valida', [App\Http\Controllers\LoginController::class, 'validaUserLogado'])->name('inicio.val');
Route::any('/login/home', [App\Http\Controllers\LoginController::class, 'valida'])->name('home.login');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/usuario/alterar-senha', [UsuarioController::class, 'alteraSenha']);
Route::any('/usuario/gravaSenha', [UsuarioController::class, 'gravaSenha']);


/*Gerenciar funcionario*/

Route::middleware('rotas:1')->group(function () {
    Route::get('/gerenciar-funcionario', [GerenciarFuncionarioController::class, 'index'])->name('gerenciar');
    Route::get('/informar-dados', [GerenciarFuncionarioController::class, 'create']);
    Route::any('/incluir-funcionario', [GerenciarFuncionarioController::class, 'store']);
    Route::get('/editar-funcionario/{idp}', [GerenciarFuncionarioController::class, 'edit']);
    Route::get('/excluir-funcionario/{idp}', [GerenciarFuncionarioController::class, 'delete']);
    Route::get('/pessoa-funcionario/{idp}', [GerenciarFuncionarioController::class, 'delete']);
    Route::get('/retorna-cidade-dados-residenciais/{id}', [GerenciarFuncionarioController::class, 'retornaCidadeDadosResidenciais']);
    Route::any('/atualizar-funcionario/{idp}', [GerenciarFuncionarioController::class, 'update'])->name('atualizar.funcionario');
    Route::get('/visualizar-funcionario/{idp}/{idf}', [GerenciarFuncionarioController::class, 'show']);
});

/*Gerenciar usuário*/

Route::middleware('rotas:2')->group(function () {
    Route::get('/gerenciar-usuario', [UsuarioController::class, 'index']);
    Route::get('/usuario-incluir', [UsuarioController::class, 'create']);
    Route::any('/cadastrar-usuarios/configurar/{id}', [UsuarioController::class, 'configurarUsuario']);
    Route::any('/cad-usuario/inserir', [UsuarioController::class, 'store']);
    Route::any('/usuario/excluir/{id}', [UsuarioController::class, 'destroy']);
    Route::any('/usuario/alterar/{id}', [UsuarioController::class, 'edit']);
    Route::any('usuario-atualizar/{id}', [UsuarioController::class, 'update']);
    Route::any('/usuario/gerar-Senha/{id}', [UsuarioController::class, 'gerarSenha']);
});


//Route::get('/gerenciar-voluntario', [App\Http\Controllers\GerenciarVoluntarioController::class, 'index'])->name('gerenciar-voluntario');
//Route::get('/incluir-voluntario', [App\Http\Controllers\GerenciarVoluntarioController::class, 'store']);


/*Gerenciar dados Bancarios*/

Route::middleware('rotas:3')->group(function () {
    Route::get('/gerenciar-dados-bancarios/{id}', [GerenciarDadosBancariosController::class, 'index'])->name('index.dadosbancarios.funcionario');
    Route::any('/incluir-dados-bancarios-funcionario/{id}', [GerenciarDadosBancariosController::class, 'create'])->name('create.dadosbancarios.funcionario');
    Route::any('/armazenar-dados-bancarios-funcionario/{id}', [GerenciarDadosBancariosController::class, 'store'])->name('store.dadosbancarios.funcionario');
    Route::any('/deletar-dado-bancario-funcionario/{id}', [GerenciarDadosBancariosController::class, 'destroy'])->name('destroy.dadosbancarios.funcionario');
    Route::any('/editar-dado-bancario-funcionario/{id}', [GerenciarDadosBancariosController::class, 'edit'])->name('edit.dadosbancarios.funcionario');
    Route::any('/alterar-dado-bancario-funcionario/{id}', [GerenciarDadosBancariosController::class, 'update'])->name('update.dadosbancarios.funcionario');
    Route::any('/recebe-agencias/{id}', [GerenciarDadosBancariosController::class, 'agencias']);
});

// Gerenciar Base Salarial

Route::middleware('rotas:23')->group(function () {
    Route::any('/gerenciar-base-salarial/{idf}', [GerenciarBaseSalarialController::class, 'index'])->name('GerenciarBaseSalarialController');
    Route::any('/incluir-base-salarial/{idf}', [GerenciarBaseSalarialController::class, 'create'])->name('IncluirBaseSalarial');
    Route::any('/vizualizar-base-salarial/{idf}', [GerenciarBaseSalarialController::class, 'show'])->name('VisualizarBaseSalarial');
    Route::any('/armazenar-base-salarial/{idf}', [GerenciarBaseSalarialController::class, 'store'])->name('ArmazenarBaseSalarial');
    Route::any('/editar-base-salarial/{idf}', [GerenciarBaseSalarialController::class, 'edit'])->name('EditarBaseSalarial');
    Route::any('/atualizar-base-sallarial/{idf}', [GerenciarBaseSalarialController::class, 'update'])->name('AtualizarBaseSalarial');
    Route::any('/retorna-cargos-editar/{id}', [GerenciarBaseSalarialController::class, 'retornaCargos']);
    Route::any('/retorna-funcao-gratificada', [GerenciarBaseSalarialController::class, 'retornaFG']);
});
/*Gerenciar setores*/
Route::middleware('rotas:4')->group(function () {
    Route::get('/gerenciar-setor', [GerenciarSetoresController::class, 'index'])->name('index.setor');
    Route::get('/pesquisar-setor', [GerenciarSetoresController::class, 'index'])->name('pesquisar-setor');
    Route::get('/incluir-setor', [GerenciarSetoresController::class, 'create']);
    Route::post('/incluir-setores', [GerenciarSetoresController::class, 'store']);
    Route::get('/editar-setor/{ids}', [GerenciarSetoresController::class, 'edit']);
    Route::get('/carregar-dados/{ids}', [GerenciarSetoresController::class, 'carregar_dados'])->name('substituir');
    Route::post('/substituir-setor/{ids}', [GerenciarSetoresController::class, 'subst']);
    Route::get('/setor-pessoas', [GerenciarSetoresController::class, 'consult']);
    Route::post('/atualizar-setor/{ids}', [GerenciarSetoresController::class, 'update']);
    Route::get('/excluir-setor/{ids}', [GerenciarSetoresController::class, 'delete']);
    Route::get('/visualizar-setor/{ids}', [GerenciarSetoresController::class, 'show'])->name('show.setor');
});

/*Gerenciar-Hierarquia*/

Route::middleware('rotas:5')->group(function () {
    Route::get('/gerenciar-hierarquia', [GerenciarHierarquiaController::class, 'index'])->name('gerenciar-hierarquia');
    Route::get('/obter-setores/{id_nivel}', [GerenciarHierarquiaController::class, 'obterSetoresPorNivel']);
    Route::get('/editar-hierarquia/{ids}', [GerenciarHierarquiaController::class, 'edit']);
    Route::post('/atualizar-hierarquia/{ids}', [GerenciarHierarquiaController::class, 'atualizarhierarquia']);
});

/*Gerenciar-Associado*/

Route::middleware('rotas:6')->group(function () {
    Route::get('/pesquisar-associado', [GerenciarAssociadoController::class, 'index'])->name('pesquisar');
    Route::get('/gerenciar-associado', [GerenciarAssociadoController::class, 'index'])->name('gerenciar-associado');
    Route::get('/informar-dados-associado', [GerenciarAssociadoController::class, 'create']);
    Route::get('/retorna-cidade-dados-residenciais/{id}', [GerenciarAssociadoController::class, 'retornaCidadeDadosResidenciais']);
    Route::any('/incluir-associado', [GerenciarAssociadoController::class, 'store']);
    Route::get('/editar-associado/{id}', [GerenciarAssociadoController::class, 'edit']);
    Route::post('/atualizar-associado/{ida}/{idp}', [GerenciarAssociadoController::class, 'update']);
    Route::get('/editar-associado/{id}', [GerenciarAssociadoController::class, 'edit']);
    Route::get('/documento-associado/{id}', [GerenciarAssociadoController::class, 'documentobancariopdf']);
    Route::get('/excluir-associado/{id}', [GerenciarAssociadoController::class, 'delete']);
    Route::post('/inativar-associado/{ida}', [GerenciarAssociadoController::class, 'inativar']);
    Route::post('/salvar-documento-associado/{id}', [GerenciarAssociadoController::class, 'salvardocumento']);
    Route::get('/visualizar-arquivo/{id}', [GerenciarAssociadoController::class, 'visualizardocumento']);
});
/*Dados Bancarios Associado*/
Route::middleware('rotas:7')->group(function () {
    Route::get('/gerenciar-dados_bancarios/{id}', [GerenciarDadosBancariosAssociadoController::class, 'index'])->name('gerenciar-dados-bancario-associado');
    Route::get('/incluir-dados-bancarios/{id}', [GerenciarDadosBancariosAssociadoController::class, 'store'])->name('dados-bancarios-associado.insert');
    Route::any('/incluir-dados_bancarios-associado/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'incluirdadosbancarios']);
    Route::get('/editar-dados-bancarios-associado/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'edit']);
    Route::any('/atualizar-dados-bancarios-associado/{ida}/{idt}/{idb}/{idc}', [GerenciarDadosBancariosAssociadoController::class, 'update']);
    Route::get('/documento-bancario/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'documentobancariopdf']);
    Route::post('/carregar-documento', [GerenciarDadosBancariosAssociadoController::class, 'carregar_documento']);
    Route::post('/salvar-documento-bancario/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'salvardocumentobancario']);
    Route::get('/visualizar-arquivo-bancario/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'visualizardocumentobancario']);
    Route::get('/excluir-dados-bancarios-associado/{ida}', [GerenciarDadosBancariosAssociadoController::class, 'delete']);
});
/*PhotoController*/
Route::get('/capture-photo/{id}', [PhotoController::class, 'showCaptureForm'])->name('capture.form');
Route::post('/store-photo/{ida}', [PhotoController::class, 'storeCapturedPhoto'])->name('store.photo');
Route::any('/visualizar-foto', [PhotoController::class, 'visualizarfoto']);


/*Rotas dos Dependentes */
Route::middleware('rotas:8')->group(function () {
    Route::get('/gerenciar-dependentes/{id}', [GerenciarDependentesController::class, 'index'])->name('IndexGerenciarDependentes');
    Route::get('/incluir-dependentes/{id}', [GerenciarDependentesController::class, 'create']);
    Route::any('/armazenar-dependentes/{id}', [GerenciarDependentesController::class, 'store']);
    Route::any('/deletar-dependentes/{id}', [GerenciarDependentesController::class, 'destroy']);
    Route::any('/editar-dependentes/{id}', [GerenciarDependentesController::class, 'edit']);
    Route::any('/atualizar-dependentes/{id}', [GerenciarDependentesController::class, 'update']);
});


/** Rotas dos Certificados */

Route::middleware('rotas:9')->group(function () {
    Route::get('/gerenciar-certificados/{id}', [GerenciarCertificadosController::class, 'index'])->name('viewGerenciarCertificados');
    Route::get('/incluir-certificados/{id}', [GerenciarCertificadosController::class, 'create']);
    Route::any('/adicionar-certificado/{id}', [GerenciarCertificadosController::class, 'store']);
    Route::any('/deletar-certificado/{id}', [GerenciarCertificadosController::class, 'destroy']);
    Route::any('/editar-certificado/{id}', [GerenciarCertificadosController::class, 'edit']);
    Route::any('/atualizar-certificado/{id}', [GerenciarCertificadosController::class, 'update']);
});


/**Rota para Entidades Escolares */

Route::middleware('rotas:24')->group(function () {
    Route::get('/gerenciar-entidades-de-ensino', [GerenciarEntidadesController::class, 'index'])->name('IndexGerenciarEntidades');
    Route::get('/incluir-entidades-ensino', [GerenciarEntidadesController::class, 'create']);
    Route::any('/armazenar-entidade', [GerenciarEntidadesController::class, 'store']);
    Route::any('/excluir-entidade/{id}', [GerenciarEntidadesController::class, 'destroy']);
    Route::any('/editar-entidade/{id}', [GerenciarEntidadesController::class, 'edit']);
    Route::any('/atualizar-entidade-ensino/{id}', [GerenciarEntidadesController::class, 'update']);
});

/* Rota Para Tipos de Contrato*/

Route::middleware('rotas:10')->group(function () {
    Route::get('/gerenciar-contrato/{id}', [GerenciarContratoController::class, 'index'])->name('indexGerenciarContrato');
    Route::get('/incluir-contrato/{id}', [GerenciarContratoController::class, 'create']);
    Route::any('/armazenar-contrato/{id}', [GerenciarContratoController::class, 'store']);
    Route::any('/excluir-contrato/{id}', [GerenciarContratoController::class, 'destroy']);
    Route::any('/editar-contrato/{id}', [GerenciarContratoController::class, 'edit']);
    Route::any('/atualizar-contrato/{id}', [GerenciarContratoController::class, 'update']);
    Route::post('/inativar-contrato/{idf}', [GerenciarContratoController::class, 'inativar']);
});

/**Rotas para Cargos**/

Route::middleware('rotas:25')->group(function () {
    Route::any('/gerenciar-cargos', [GerenciarCargosController::class, 'index'])->name('gerenciar.cargos');
    Route::any('/incluir-cargos', [GerenciarCargosController::class, 'create']);
    Route::any('/editar-cargos/{id}', [GerenciarCargosController::class, 'edit'])->name('Editar');
    Route::any('/armazenar-cargo', [GerenciarCargosController::class, 'store'])->name('armazenaCargo');
    Route::any('/deletar-cargos/{id}', [GerenciarCargosController::class, 'destroy']);
    Route::any('/vizualizar-historico/{id}', [GerenciarCargosController::class, 'show'])->name('visualizarHistoricoCargo');
    Route::any('/atualiza-cargo/{id}', [GerenciarCargosController::class, 'update'])->name('AtualizaCargo');
});

/**Rotas para Tipo de Desconto**/

Route::get('/gerenciar-tipo-desconto', [GerenciarTipoDescontoController::class, 'index'])->name('indexTipoDesconto');
Route::get('/incluir-tipo-desconto', [GerenciarTipoDescontoController::class, 'create']);
Route::get('/editar-tipo-desconto/{id}', [GerenciarTipoDescontoController::class, 'edit']);
Route::post('/armazenar-tipo-desconto', [GerenciarTipoDescontoController::class, 'store']);
Route::any('/atualizar-tipo-desconto/{id}', [GerenciarTipoDescontoController::class, 'update']);
Route::any('/exluir-tipo-desconto/{id}', [GerenciarTipoDescontoController::class, 'destroy']);
Route::any('/renovar-tipo-desconto/{id}', [GerenciarTipoDescontoController::class, 'renew']);
Route::any('/modificar-tipo-desconto/{id}', [GerenciarTipoDescontoController::class, 'modify']);


/**Gerenciar Ferias**/
/*Administrador Setor*/
Route::middleware('rotas:13')->group(function () {
    Route::get('/periodo-de-ferias', [GerenciarFeriasController::class, 'index'])->name('IndexGerenciarFerias');
    Route::get('/incluir-ferias/{id}', [GerenciarFeriasController::class, 'create'])->name('CriarFerias');
    Route::any('/armazenar-ferias/{id}', [GerenciarFeriasController::class, 'update'])->name('ArmazenarFerias');
    Route::any('/visualizar-historico-recusa-ferias/{id}', [GerenciarFeriasController::class, 'show'])->name('HistoricoRecusaFerias');
    Route::any('/enviar-ferias', [GerenciarFeriasController::class, 'enviarFerias'])->name('enviar-ferias');
    Route::any('/reabrir-formulario/{id}', [GerenciarFeriasController::class, 'reabrirFormulario'])->name('ReabrirFormulario');
    Route::get('/retorna-periodo-ferias/{ano}/{nome}/{setor}/{status}', [GerenciarFeriasController::class, 'retornaPeriodoFerias']);
    Route::any('/enviar-ferias-individual/{id}', [GerenciarFeriasController::class, 'EnviaPeriodoDeFeriasIndividualmente'])->name('EnviaFeriasIndividual');
});

/*Administrador DAF*/
Route::middleware('rotas:12')->group(function () {
    Route::any('/abrir-ferias', [GerenciarFeriasController::class, 'InsereERetornaFuncionarios'])->name('AbreFerias');
    Route::any('/administrar-ferias', [GerenciarFeriasController::class, 'administraferias'])->name('AdministrarFerias');
    Route::any('/autorizar-ferias/{id}', [GerenciarFeriasController::class, 'autorizarferias'])->name('autorizarFerias');
    Route::any('/formulario-recusar-ferias/{id}', [GerenciarFeriasController::class, 'formulario_recusa_periodo_de_ferias'])->name('FormularioFerias');
    Route::any('/recusa-ferias/{id}', [GerenciarFeriasController::class, 'recusa_pedido_de_ferias'])->name('RecusaFerias');
    Route::get('/retorna-periodo-ferias/{ano}/{nome}/{setor}', [GerenciarFeriasController::class, 'retornaPeriodoFerias']);
    Route::any('/excluir-ferias/{id}', [GerenciarFeriasController::class, 'destroy'])->name('ExcluiFerias');
});
/**Rotas de Entrada**/


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home');


/*Gerenciar Afastamentos*/

Route::middleware('rotas:14')->group(function () {
    Route::any('/gerenciar-afastamentos/{idf}', [GerenciarAfastamentosController::class, 'index'])->name('indexGerenciarAfastamentos');
    Route::any('/incluir-afastamentos/{idf}', [GerenciarAfastamentosController::class, 'create']);
    Route::any('/editar-afastamentos/{idf}', [GerenciarAfastamentosController::class, 'edit']);
    Route::any('/armazenar-afastamentos/{idf}', [GerenciarAfastamentosController::class, 'store']);
    Route::any('/excluir-afastamento/{idf}', [GerenciarAfastamentosController::class, 'destroy']);
    Route::any('/atualizar-afastamento/{idf}', [GerenciarAfastamentosController::class, 'update']);
    Route::get('/afastamento/complemento/{id}', [GerenciarAfastamentosController::class, 'getComplemento']);
});

/*Controle de Efetivo*/

Route::middleware('rotas:15')->group(function () {
    Route::get('/gerenciar-efetivo', [GerenciarEfetivoController::class, 'index'])->name('gerenciar-efetivo');
});

/*Controle de Vagas*/
Route::middleware('rotas:16')->group(function () {
    Route::get('/controle-vagas', [ControleVagasController::class, 'index'])->name('indexControleVagas');
    Route::any('/incluir-vagas', [ControleVagasController::class, 'create']);
    Route::any('/armazenar-vagas', [ControleVagasController::class, 'store']);
    Route::any('/editar-vagas/{idVagas}', [ControleVagasController::class, 'edit']);
    Route::any('/excluir-vagas/{idC}', [ControleVagasController::class, 'destroy']);
    Route::any('/atualizar-vagas/{idC}', [ControleVagasController::class, 'update']);
});

/*Controle da Data Limite de Férias*/
Route::middleware('rotas:17')->group(function () {
    Route::any('/gerenciar-dia-limite-ferias', [GerenciarDataLimiteDeFeriasController::class, 'index'])->name('index.gerenciar-dia-limite-ferias');
    Route::any('/criar-dia-limite-ferias', [GerenciarDataLimiteDeFeriasController::class, 'create'])->name('create.gerenciar-dia-limite-ferias');
    Route::any('/armazenar-dia-limite-ferias', [GerenciarDataLimiteDeFeriasController::class, 'store'])->name('store.gerenciar-dia-limite-ferias');
});
/*Controle de Férias*/
Route::middleware('rotas:18')->group(function () {
    Route::get('/controle-ferias', [ControleFeriasController::class, 'index'])->name('indexControleFerias');
});
/*Ajax Controller */
Route::get('/retorna-cidades/{id}', [AjaxController::class, 'retornaCidades']);
Route::get('/retorna-dados-endereco/{id}', [AjaxController::class, 'getAddressByCep']);


/*Tipos de Contrato*/
Route::middleware('rotas:19')->group(function () {
    Route::any('/gerenciar-tipos-de-contrato', [GerenciarTipoDeContratoController::class, 'index'])->name('index.tipos-de-contrato');
    Route::any('/incluir-tipos-de-contrato', [GerenciarTipoDeContratoController::class, 'create'])->name('create.tipos-de-contrato');
    Route::any('/armazenar-tipos-de-contrato', [GerenciarTipoDeContratoController::class, 'store'])->name('store.tipos-de-contrato');
    Route::any('/editar-tipos-de-contrato/{id}', [GerenciarTipoDeContratoController::class, 'edit'])->name('edit.tipos-de-contrato');
    Route::any('/atualizar-tipos-de-contrato/{id}', [GerenciarTipoDeContratoController::class, 'update'])->name('update.tipos-de-contrato');
    Route::any('/deletar-tipos-de-contrato/{id}', [GerenciarTipoDeContratoController::class, 'destroy'])->name('destroy.tipos-de-contrato');
});
/*Gerenciar Perfis*/
Route::middleware('rotas:20')->group(function () {
    Route::get('/gerenciar-perfis', [GerenciarPerfil::class, 'index']);
    Route::get('/criar-perfis', [GerenciarPerfil::class, 'create']);
    Route::post('/armazenar-perfis', [GerenciarPerfil::class, 'store']);
    Route::get('/visualizar-perfis/{id}', [GerenciarPerfil::class, 'show']);
    Route::get('/editar-perfis/{id}', [GerenciarPerfil::class, 'edit']);
    Route::post('/atualizar-perfis/{id}', [GerenciarPerfil::class, 'update']);
    Route::any('/excluir-perfis/{id}', [GerenciarPerfil::class, 'destroy']);
});


// Gerenciar Rotas Setor
Route::middleware('rotas:21')->group(function () {
    Route::get('/gerenciar-setor-usuario', [GerenciarSetor::class, 'index']);
    Route::get('/criar-setor-usuario', [GerenciarSetor::class, 'create']);
    Route::post('/armazenar-setor-usuario', [GerenciarSetor::class, 'store']);
    Route::get('/visualizar-setor-usuario/{id}', [GerenciarSetor::class, 'show']);
    Route::get('/editar-setor-usuario/{id}', [GerenciarSetor::class, 'edit']);
    Route::post('/atualizar-setor-usuario/{id}', [GerenciarSetor::class, 'update']);
    Route::any('/excluir-setor-usuario/{id}', [GerenciarSetor::class, 'destroy']);
});
