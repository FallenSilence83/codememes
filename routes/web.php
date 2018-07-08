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

$router->get('/', [
    'as' => 'home', 'uses' => 'HomeController@home'
]);

//room endpoints
$router->get('/room', [
    'as' => 'room', 'uses' => 'RoomController@room'
]);
$router->post('/room', [
    'as' => 'room', 'uses' => 'RoomController@room'
]);
$router->get('/room/status', [
    'as' => 'room.status', 'uses' => 'RoomController@status'
]);
$router->get('/room/extend', [
    'as' => 'room.extend', 'uses' => 'RoomController@extend'
]);
$router->get('/room/create', [
    'as' => 'room.create', 'uses' => 'RoomController@create'
]);
$router->get('/room/join', [
    'as' => 'room.join', 'uses' => 'RoomController@join'
]);
$router->get('/room/team', [
    'as' => 'room.team', 'uses' => 'RoomController@joinTeam'
]);
$router->get('/room/newgame', [
    'as' => 'room.newgame', 'uses' => 'RoomController@newGame'
]);
$router->get('/room/clue', [
    'as' => 'room.clue', 'uses' => 'RoomController@clue'
]);
$router->get('/room/guess', [
    'as' => 'room.guess', 'uses' => 'RoomController@guess'
]);
$router->get('/room/pass', [
    'as' => 'room.pass', 'uses' => 'RoomController@pass'
]);

//user endpoints
$router->get('/user/status', [
    'as' => 'user.status', 'uses' => 'UserController@status'
]);
$router->get('/user/logout', [
    'as' => 'user.logout', 'uses' => 'UserController@logout'
]);

$router->post('/user/update', [
    'as' => 'user.update', 'uses' => 'UserController@updateUser'
]);
