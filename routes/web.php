<?php

//  Rutas principales
Route::get('/', ['uses' => 'Index\IndexController@index', 'as' => 'index.index']);
Route::post('/login', ['uses' => 'Index\IndexController@login', 'as' => 'index.login']);
Route::get('/register', ['uses' => 'Index\IndexController@register', 'as' => 'index.register']);
Route::post('/register', ['uses' => 'Index\IndexController@registerUser', 'as' => 'index.registerUser']);
Route::get('/emailExists/{email}', ['uses' => 'Index\IndexController@emailExists', 'as' => 'index.emailExists']);
Route::get('/logout', ['uses' => 'Index\IndexController@logout', 'as' => 'index.logout']);

//  Rutas con autenticacion
Route::group(['prefix' => 'user'], function() {
    Route::get('/', ['uses' => 'User\IndexController@index', 'as' => 'user.index']);
    Route::get('/config', ['uses' => 'User\IndexController@config', 'as' => 'user.config']);
    Route::post('/bankUpdate', ['uses' => 'User\IndexController@bankUpdate', 'as' => 'user.bankUpdate']);
    Route::post('/changePassword', ['uses' => 'User\IndexController@changePassword', 'as' => 'user.changePassword']);
    Route::get('/results', ['uses' => 'User\IndexController@results', 'as' => 'user.results']);
    Route::post('/setGain', ['uses' => 'User\IndexController@setGain', 'as' => 'user.setGain']);

    Route::resource('ticket', 'User\TicketController');
    Route::resource('transfer', 'User\TransferController');
    Route::post('transfer/{transfer}/changeStatus/{status}', ['uses' => 'User\TransferController@changeStatus', 'as' => 'transfer.changeStatus']);
    Route::resource('withdraw', 'User\WithdrawController');
    Route::post('withdraw/{withdraw}/changeStatus/{status}', ['uses' => 'User\WithdrawController@changeStatus', 'as' => 'withdraw.changeStatus']);
});