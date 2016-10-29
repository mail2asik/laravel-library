<?php
/**
 * Admin routes
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

Route::group(['prefix' => 'admin', 'middleware' => 'adminAuth'], function () {

    // Auth
    Route::get('/login', ['as' => 'admin.login', 'uses' => 'Admin\AuthController@login']);
    Route::get('/dashboard', ['as' => 'admin.dashboard', 'uses' => 'Admin\DashboardController@index']);
    Route::get('/change-password', ['as' => 'admin.changePassword', 'uses' => 'Admin\SettingController@changePassword']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doChangePassword', ['as' => 'admin.postChangePassword', 'uses' => 'Admin\SettingController@doChangePassword']);
    });
    Route::get('/change-profile', ['as' => 'admin.changeProfile', 'uses' => 'Admin\SettingController@changeProfile']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doChangeProfile', ['as' => 'admin.postChangeProfile', 'uses' => 'Admin\SettingController@doChangeProfile']);
    });

    // Users
    Route::get('users', ['as' => 'admin.users', 'uses' => 'admin\UserController@index']);
    Route::get('/users/create', ['as' => 'admin.users.create', 'uses' => 'admin\UserController@create']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doCreateUser', ['as' => 'admin.postCreateUser', 'uses' => 'admin\UserController@doCreateUser']);
    });
    Route::get('/users/update/{user_uid}', ['as' => 'admin.users.update', 'uses' => 'admin\UserController@update']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doUpdateUser/{user_uid}', ['as' => 'admin.postUpdateUser', 'uses' => 'admin\UserController@doUpdateUser']);
    });
    Route::get('/users/delete/{user_uid}', ['as' => 'admin.users.delete', 'uses' => 'admin\UserController@delete']);

});
