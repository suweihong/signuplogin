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


// homepage
Route::get('/', function(){
	return redirect('/oauth');
});

// OAUTH 登录
Route::get('oauth', 'Oauth\IndexController@oauth');
Route::post('oauth', 'Oauth\IndexController@doOauth');

// 双重认证[登录后进行]
Route::get('double/auth/{api_token}', 'Double\IndexController@auth');
Route::post('double/auth', 'Double\IndexController@doAuth');


// 注册过程
Route::get('signup', 'Signup\IndexController@index')->middleware('cross');
Route::post('signup', 'Signup\IndexController@indexd');

// 验证完成 设置密码
Route::get('signup/verified/{token}', 'Signup\IndexController@verified');
Route::post('signup/verified', 'Signup\IndexController@doVerified');

// 初始化双重认证
Route::get('double/init/{token}', 'Double\IndexController@init');
Route::post('double/init', 'Double\IndexController@doInit');


// 忘记密码
Route::get('forget', 'Forget\IndexController@index');
Route::post('forget', 'Forget\IndexController@doIndex');

// 忘记密码验证完成 设置新密码
Route::get('forget/verified/{token}', 'Forget\IndexController@verified');
Route::post('forget/verified', 'Forget\IndexController@doVerified');


// API 相关
// ............

Route::get('/test/','Double\IndexController@test');