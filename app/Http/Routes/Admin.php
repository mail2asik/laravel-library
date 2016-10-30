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

    // Books
    Route::get('books', ['as' => 'admin.books', 'uses' => 'admin\BookController@index']);
    Route::get('/books/create', ['as' => 'admin.books.create', 'uses' => 'admin\BookController@create']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doCreateBook', ['as' => 'admin.postCreateBook', 'uses' => 'admin\BookController@doCreateBook']);
    });
    Route::get('/books/update/{book_uid}', ['as' => 'admin.books.update', 'uses' => 'admin\BookController@update']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doUpdateBook/{book_uid}', ['as' => 'admin.postUpdateBook', 'uses' => 'admin\BookController@doUpdateBook']);
    });
    Route::get('/books/delete/{book_uid}', ['as' => 'admin.books.delete', 'uses' => 'admin\BookController@delete']);
    Route::get('/books/collect/{book_uid}/{user_uid}', ['as' => 'admin.books.collectBook', 'uses' => 'admin\BookController@collectBook']);

    // Reports
    Route::get('reports', ['as' => 'admin.reports', 'uses' => 'admin\ReportController@index']);
});
