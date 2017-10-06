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

Route::group(['prefix'=>'despesa','where'=>['id'=>'[0-9]+']], function() {
    Route::get('pdfView', ['as'=>'generate.pdf', 'uses'=>'DespesaPendenteController@toPDF']);
    Route::get('pendentes', ['as'=>'pendentes', 'uses'=>'DespesaPendenteController@index']);
    Route::post('criarPendente', ['as'=>'criar.pendente', 'uses'=>'DespesaPendenteController@create']);
    Route::post('alterarPendente', ['as'=>'alterar.pendente', 'uses'=>'DespesaPendenteController@edit']);
    Route::post('deletarPendente', ['as'=>'deletar.pendente', 'uses'=>'DespesaPendenteController@delete']);

});