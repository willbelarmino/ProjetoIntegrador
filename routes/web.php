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

Route::group(['prefix'=>'periodo','where'=>['id'=>'[0-9]+']], function() {
    Route::post('alterarPeriodo', ['as'=>'periodo.alteraMes', 'uses'=>'UtilsController@alterarPeriodoMes']);
    Route::post('alterarPeriodoData', ['as'=>'periodo.alteraData', 'uses'=>'UtilsController@alterarPeriodoData']);
});

Route::group(['prefix'=>'acesso','where'=>['id'=>'[0-9]+']], function() {
    Route::get('login', ['as'=>'login', 'uses'=>'Auth\LoginController@index']);
    Route::get('registro', ['as'=>'registro', 'uses'=>'Auth\RegisterController@index']);
    Route::post('criarCadastro', ['as'=>'criar.cadastro', 'uses'=>'Auth\RegisterController@create']);
    Route::post('entrar', ['as'=>'autenticar.usuario', 'uses'=>'Auth\LoginController@doLogin']);
});

Route::group(['prefix'=>'categoria','where'=>['id'=>'[0-9]+']], function() {
    Route::get('categorias', ['as'=>'categorias', 'uses'=>'CategoriaController@index']);
    Route::post('criarCategoria', ['as'=>'criar.categoria', 'uses'=>'CategoriaController@create']);
    Route::post('alterarCategoria', ['as'=>'alterar.categoria', 'uses'=>'CategoriaController@edit']);
    Route::post('deletarCategoria', ['as'=>'deletar.categoria', 'uses'=>'CategoriaController@delete']);
});

Route::group(['prefix'=>'conta','where'=>['id'=>'[0-9]+']], function() {
    Route::get('contas', ['as'=>'contas', 'uses'=>'ContaController@index']);
    Route::post('criarConta', ['as'=>'criar.conta', 'uses'=>'ContaController@create']);
    Route::post('alterarConta', ['as'=>'alterar.conta', 'uses'=>'ContaController@edit']);
    Route::post('deletarConta', ['as'=>'deletar.conta', 'uses'=>'ContaController@delete']);
});

Route::group(['prefix'=>'despesa','where'=>['id'=>'[0-9]+']], function() {
    Route::get('pdfView', ['as'=>'generate.pdf', 'uses'=>'DespesaPendenteController@toPDF']);
    Route::get('pendentes', ['as'=>'pendentes', 'uses'=>'DespesaPendenteController@index']);
    Route::post('criarPendente', ['as'=>'criar.pendente', 'uses'=>'DespesaPendenteController@create']);
    Route::post('alterarPendente', ['as'=>'alterar.pendente', 'uses'=>'DespesaPendenteController@edit']);
    Route::post('deletarPendente', ['as'=>'deletar.pendente', 'uses'=>'DespesaPendenteController@delete']);
});

Route::group(['prefix'=>'cartao','where'=>['id'=>'[0-9]+']], function() {
    Route::get('cartoes', ['as'=>'cartoes', 'uses'=>'CartaoController@index']);
    Route::post('criarCartao', ['as'=>'criar.cartao', 'uses'=>'CartaoController@create']);
    Route::post('alterarCartao', ['as'=>'alterar.cartao', 'uses'=>'CartaoController@edit']);
    Route::post('deletarCartao', ['as'=>'deletar.cartao', 'uses'=>'CartaoController@delete']);
});

Route::group(['prefix'=>'renda','where'=>['id'=>'[0-9]+']], function() {
    Route::get('pdfView', ['as'=>'generate.renda.pdf', 'uses'=>'RendaController@toPDF']);
    Route::get('rendas', ['as'=>'rendas', 'uses'=>'RendaController@index']);
    Route::post('criarRenda', ['as'=>'criar.renda', 'uses'=>'RendaController@create']);
    Route::post('alterarRenda', ['as'=>'alterar.renda', 'uses'=>'RendaController@edit']);
    Route::post('deletarRenda', ['as'=>'deletar.renda', 'uses'=>'RendaController@delete']);
});