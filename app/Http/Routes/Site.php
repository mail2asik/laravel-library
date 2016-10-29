<?php
/**
 * Site routes
 *
 * @author Asik
 * @email  mail2asik@gmal.com
 */

// User end (Public Pages)
Route::get('/', ['as' => 'site.home', 'uses' => 'Site\BookController@home']);
Route::get('/logout', ['as' => 'site.logout', 'uses' => 'Site\AuthController@logout']);
Route::get('/activation', ['as' => 'site.activation', 'uses' => 'Site\AuthController@activation']);
Route::get('/about-us', ['as' => 'site.aboutUs', 'uses' => 'Site\StaticController@aboutUs']);

Route::group(['middleware' => 'guest'], function () {
    Route::get('/activate', ['as' => 'site.activate', 'uses' => 'Site\AuthController@doActivate']);

    Route::get('/login', ['as' => 'site.login', 'uses' => 'Site\AuthController@login']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doLogin', ['as' => 'site.postWebLogin', 'uses' => 'Site\AuthController@doLogin']);
    });

    Route::get('/forgot', ['as' => 'site.forgot', 'uses' => 'Site\AuthController@forgot']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doForgot', ['as' => 'site.postForgotPassword', 'uses' => 'Site\AuthController@doForgotPassword']);
    });

    Route::get('/reset-password', ['as' => 'site.resetPassword', 'uses' => 'Site\AuthController@resetPassword']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doResetPassword', ['as' => 'site.postResetPassword', 'uses' => 'Site\AuthController@doResetPassword']);
    });
});


// User end (Private Pages)
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', ['as' => 'site.dashboard', 'uses' => 'Site\DashboardController@index']);

    Route::get('/change-password', ['as' => 'site.changePassword', 'uses' => 'Site\AuthController@changePassword']);
    Route::group(array('middleware' => 'csrf'), function () {
        Route::post('doChangePassword', ['as' => 'site.postChangePassword', 'uses' => 'Site\AuthController@doChangePassword']);
    });

    // Books
    Route::get('books', ['as' => 'site.books', 'uses' => 'Site\BookController@books']);
    Route::get('/books/borrow/{book_uid}', ['as' => 'site.books.borrowBook', 'uses' => 'Site\BookController@borrowBook']);
    Route::get('/books/return/{book_uid}', ['as' => 'admin.books.returnBook', 'uses' => 'Site\BookController@returnBook']);
});