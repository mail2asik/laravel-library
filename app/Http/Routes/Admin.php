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
    Route::get('users', ['as' => 'admin.users', 'uses' => 'Admin\UserController@index']);
    Route::get('/users/create', ['as' => 'admin.users.create', 'uses' => 'Admin\UserController@create']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doCreateUser', ['as' => 'admin.postCreateUser', 'uses' => 'Admin\UserController@doCreateUser']);
    });
    Route::get('/users/update/{user_uid}', ['as' => 'admin.users.update', 'uses' => 'Admin\UserController@update']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doUpdateUser/{user_uid}', ['as' => 'admin.postUpdateUser', 'uses' => 'Admin\UserController@doUpdateUser']);
    });
    Route::get('/users/delete/{user_uid}', ['as' => 'admin.users.delete', 'uses' => 'Admin\UserController@delete']);

    // Books
    Route::get('books', ['as' => 'admin.books', 'uses' => 'Admin\BookController@index']);
    Route::get('/books/create', ['as' => 'admin.books.create', 'uses' => 'Admin\BookController@create']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doCreateBook', ['as' => 'admin.postCreateBook', 'uses' => 'Admin\BookController@doCreateBook']);
    });
    Route::get('/books/update/{book_uid}', ['as' => 'admin.books.update', 'uses' => 'Admin\BookController@update']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doUpdateBook/{book_uid}', ['as' => 'admin.postUpdateBook', 'uses' => 'Admin\BookController@doUpdateBook']);
    });
    Route::get('/books/delete/{book_uid}', ['as' => 'admin.books.delete', 'uses' => 'Admin\BookController@delete']);
    Route::get('/books/collect/{book_uid}/{user_uid}', ['as' => 'admin.books.collectBook', 'uses' => 'Admin\BookController@collectBook']);

    // Reports
    Route::get('reports', ['as' => 'admin.reports', 'uses' => 'Admin\ReportController@index']);
});
