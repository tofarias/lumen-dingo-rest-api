<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/usuarios', 'UsuarioController@mostrarTodosUsuarios');

$router->group(['prefix' => 'usuario'], function () use ($router){

    $router->get('{id}', 'UsuarioController@mostrarUmUsuario');

    $router->post('cadastrar', '\App\Http\Controllers\UsuarioController@cadastrarUsuario');

    $router->put('{id}/atualizar', 'UsuarioController@atualizarUsuario');

    $router->delete('{id}/deletar', 'UsuarioController@deletarUsuario');

});

$router->post('login', 'UsuarioController@usuarioLogin');
$router->post('info', 'UsuarioController@mostrarUsuarioAutenticado');
$router->post('logout', 'UsuarioController@usuarioLogout');
