<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});
//登录
Route::any('login','LoginController@login');
//注册
Route::any('reg','LoginController@reg');
//短信验证
Route::any('duanxin','LoginController@duanxin');
//滑动验证码
Route::any('qaptcha','LoginController@qaptcha');
//用户名验证
Route::any('reg_uname','LoginController@reg_uname');
//邮箱验证
Route::any('em','LoginController@em');
//电话号码验证
Route::any('reg_tel','LoginController@reg_tel');
//密码验证
Route::any('reg_pwd','LoginController@reg_pwd');
//确认密码验证
Route::any('reg_cpwd','LoginController@reg_cpwd');



Route::any('oneself','OneselfController@oneself');
Route::any('xueli','OneselfController@xueli');
Route::any('selfclass','OneselfController@selfclass');
Route::any('pays','OneselfController@pays');
Route::any('pays_money','OneselfController@pays_money');
Route::any('yu_e','OneselfController@yu_e');
Route::any('wenti','OneselfController@wenti');
Route::any('wenti_da','OneselfController@wenti_da');



Route::any('grade','GradeController@grade');
Route::any('class','GradeController@gclass');
Route::any('subject','GradeController@subject');
Route::any('dofenlei','GradeController@dofenlei');
Route::any('allrank','GradeController@allrank');
Route::any('fenrank','GradeController@fenrank');
Route::any('info','GradeController@info');
Route::any('indexinfo','GradeController@indexinfo');
Route::any('classmate','GradeController@classmate');