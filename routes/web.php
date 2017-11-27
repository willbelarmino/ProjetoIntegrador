<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'Auth\LoginController@index');
Route::get('sair', 'Auth\LoginController@sair');
Route::get('registro', 'Auth\RegisterController@index');
Route::post('criar.cadastro', 'Auth\RegisterController@create');
Route::post('autenticar.usuario', 'Auth\LoginController@doLogin');
Route::get('autenticar.usuario', 'Auth\LoginController@doLogin');
Route::get('page.error', 'UtilsController@error');


/*
Route::group(['prefix'=>'acesso','where'=>['id'=>'[0-9]+']], function() {
    Route::get('sair', ['as'=>'sair', 'uses'=>'Auth\LoginController@sair']);
    Route::get('registro', ['as'=>'registro', 'uses'=>'Auth\RegisterController@index']);
    Route::post('criarCadastro', ['as'=>'criar.cadastro', 'uses'=>'Auth\RegisterController@create']);
    Route::post('entrar', ['as'=>'autenticar.usuario', 'uses'=>'Auth\LoginController@doLogin']);
});

*/

Route::group(['prefix'=>'periodo','where'=>['id'=>'[0-9]+']], function() {
    Route::post('alterarPeriodo', ['as'=>'periodo.alteraMes', 'uses'=>'UtilsController@alterarPeriodoMes']);
    Route::post('alterarPeriodoData', ['as'=>'periodo.alteraData', 'uses'=>'UtilsController@alterarPeriodoData']);
});

Route::group(['prefix'=>'dashboard','where'=>['id'=>'[0-9]+']], function() {
    Route::get('home', ['as'=>'home', 'uses'=>'HomeController@index']);
    Route::post('home', ['as'=>'home', 'uses'=>'HomeController@index']);
});

Route::group(['prefix'=>'usuario','where'=>['id'=>'[0-9]+']], function() {
    Route::get('minhaConta', ['as'=>'minha.conta', 'uses'=>'UsuarioController@index']);
    Route::post('alterarDados', ['as'=>'alterar.dados', 'uses'=>'UsuarioController@edit']);
    Route::post('encerrarCadastro', ['as'=>'encerrar.cadastro', 'uses'=>'UsuarioController@delete']);
});

Route::group(['prefix'=>'categoria','where'=>['id'=>'[0-9]+']], function() {
    Route::get('categorias', ['as'=>'categorias', 'uses'=>'CategoriaController@index']);
    Route::post('criarCategoria', ['as'=>'criar.categoria', 'uses'=>'CategoriaController@create']);
    Route::post('alterarCategoria', ['as'=>'alterar.categoria', 'uses'=>'CategoriaController@edit']);
    Route::post('deletarCategoria', ['as'=>'deletar.categoria', 'uses'=>'CategoriaController@delete']); 
    Route::post('verExtratoCategoria', ['as'=>'view-extrato-categoria', 'uses'=>'CategoriaController@view']);
    Route::get('verExtratoCategoria', ['as'=>'view-extrato-categoria', 'uses'=>'CategoriaController@view']); 
});

Route::group(['prefix'=>'conta','where'=>['id'=>'[0-9]+']], function() {
    Route::get('contas', ['as'=>'contas', 'uses'=>'ContaController@index']);
    Route::post('criarConta', ['as'=>'criar.conta', 'uses'=>'ContaController@create']);
    Route::post('alterarConta', ['as'=>'alterar.conta', 'uses'=>'ContaController@edit']);
    Route::post('deletarConta', ['as'=>'deletar.conta', 'uses'=>'ContaController@delete']);
    Route::post('verExtrato', ['as'=>'extrato-conta', 'uses'=>'HomeController@visualizarExtratoConta']);
    Route::get('verExtrato', ['as'=>'extrato-conta', 'uses'=>'HomeController@visualizarExtratoConta']);
    Route::get('pdfViewExtrato', ['as'=>'relatorio.conta', 'uses'=>'HomeController@toPDF']);
});

Route::group(['prefix'=>'despesa','where'=>['id'=>'[0-9]+']], function() {
    Route::get('pdfViewPendente', ['as'=>'generate.relPendente', 'uses'=>'DespesaPendenteController@toPDF']);
    Route::get('pendentes', ['as'=>'pendentes', 'uses'=>'DespesaPendenteController@index']);   
    Route::post('criarPendente', ['as'=>'criar.pendente', 'uses'=>'DespesaPendenteController@create']);
    Route::post('alterarPendente', ['as'=>'alterar.pendente', 'uses'=>'DespesaPendenteController@edit']);
    Route::post('deletarPendente', ['as'=>'deletar.pendente', 'uses'=>'DespesaPendenteController@delete']);
    Route::post('pagarDespesa', ['as'=>'pagar', 'uses'=>'DespesaPendenteController@pagar']);

    Route::get('pdfViewPaga', ['as'=>'generate.relPaga', 'uses'=>'DespesaPagaController@toPDF']);
    Route::get('pagas', ['as'=>'pagas', 'uses'=>'DespesaPagaController@index']);
    Route::post('criarPaga', ['as'=>'criar.paga', 'uses'=>'DespesaPagaController@create']);
    Route::post('alterarPaga', ['as'=>'alterar.paga', 'uses'=>'DespesaPagaController@edit']);
    Route::post('deletarPaga', ['as'=>'deletar.paga', 'uses'=>'DespesaPagaController@delete']);
});

Route::group(['prefix'=>'cartao','where'=>['id'=>'[0-9]+']], function() {
    Route::get('cartoes', ['as'=>'cartoes', 'uses'=>'CartaoController@index']);
    Route::post('criarCartao', ['as'=>'criar.cartao', 'uses'=>'CartaoController@create']);
    Route::post('alterarCartao', ['as'=>'alterar.cartao', 'uses'=>'CartaoController@edit']);
    Route::post('deletarCartao', ['as'=>'deletar.cartao', 'uses'=>'CartaoController@delete']);
});

Route::group(['prefix'=>'renda','where'=>['id'=>'[0-9]+']], function() {
    Route::get('pdfViewRenda', ['as'=>'generate.relRenda.pdf', 'uses'=>'RendaController@toPDF']);
    Route::get('rendas', ['as'=>'rendas', 'uses'=>'RendaController@index']);
    Route::post('criarRenda', ['as'=>'criar.renda', 'uses'=>'RendaController@create']);
    Route::post('alterarRenda', ['as'=>'alterar.renda', 'uses'=>'RendaController@edit']);
    Route::post('deletarRenda', ['as'=>'deletar.renda', 'uses'=>'RendaController@delete']);
    Route::post('cancelarRenda', ['as'=>'cancelar.renda', 'uses'=>'RendaController@cancel']);
    Route::get('viewRendas', ['as'=>'popula.rendas', 'uses'=>'RendaController@populaTabela']);
});

Auth::routes();







