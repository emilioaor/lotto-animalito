<?php

//  Rutas principales
Route::group(['middleware' => 'guest'], function() {
    // Usuarios sin autenticacion
    Route::get('/', ['uses' => 'Index\IndexController@index', 'as' => 'index.index']);
    Route::post('/login', ['uses' => 'Index\IndexController@login', 'as' => 'index.login']);
    Route::get('/register', ['uses' => 'Index\IndexController@register', 'as' => 'index.register']);
    Route::post('/register', ['uses' => 'Index\IndexController@registerUser', 'as' => 'index.registerUser']);
    Route::get('/emailExists/{email}', ['uses' => 'Index\IndexController@emailExists', 'as' => 'index.emailExists']);
    Route::get('/passwordReset', ['uses' => 'Index\IndexController@passwordReset', 'as' => 'index.passwordReset']);
    Route::post('/restorePassword', ['uses' => 'Index\IndexController@restorePassword', 'as' => 'index.restorePassword']);
});

//  Rutas con autenticacion
Route::get('/logout', ['uses' => 'Index\IndexController@logout', 'as' => 'index.logout'])->middleware('check');

Route::group(['prefix' => 'user', 'middleware' => 'check'], function() {
    Route::get('/', ['uses' => 'User\IndexController@index', 'as' => 'user.index']);
    Route::get('/config', ['uses' => 'User\IndexController@config', 'as' => 'user.config']);
    Route::post('/bankUpdate', ['uses' => 'User\IndexController@bankUpdate', 'as' => 'user.bankUpdate']);
    Route::post('/changePassword', ['uses' => 'User\IndexController@changePassword', 'as' => 'user.changePassword']);
    Route::get('/results', ['uses' => 'User\IndexController@results', 'as' => 'user.results']);
    Route::get('/graphicData', ['uses' => 'User\IndexController@graphicData', 'as' => 'user.graphicData']);

    // Middleware en el constructor del controlador
    Route::resource('ticket', 'User\TicketController');
    Route::resource('transfer', 'User\TransferController');
    Route::resource('withdraw', 'User\WithdrawController');

    // Admin
    Route::group(['middleware' => 'admin'], function() {
        Route::post('/setGain', ['uses' => 'User\IndexController@setGain', 'as' => 'user.setGain']);
        Route::post('transfer/{transfer}/changeStatus/{status}', ['uses' => 'User\TransferController@changeStatus', 'as' => 'transfer.changeStatus']);
        Route::post('withdraw/{withdraw}/changeStatus/{status}', ['uses' => 'User\WithdrawController@changeStatus', 'as' => 'withdraw.changeStatus']);

        Route::get('/report', ['uses' => 'Admin\ReportController@report', 'as' => 'user.report']);
        Route::post('/generateReport', ['uses' => 'Admin\ReportController@generateReport', 'as' => 'user.generateReport']);
    });
});