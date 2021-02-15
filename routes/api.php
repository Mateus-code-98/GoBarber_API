<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

Route::get('botao','FeedbackController@clicouBotao');

Route::get('barbearias'                   , 'UserController@showBarberShops');
Route::get('barbearias/{id}'              , 'UserController@showOnlyBarberShop');
Route::get('barbearias/{id}/agendamentos' , 'ScheduleController@pesquisarAgendamentoBarberShop');

Route::post  ('users'                     , 'UserController@store');
Route::get   ('users/{id}'                , 'UserController@show');
Route::put   ('users/{id}'                , 'UserController@update');
Route::put   ('users/{id}/changePassword' , 'UserController@updatePassword');
Route::delete('users/{id}'                , 'UserController@destroy');
Route::post  ('users/avatar/{id}'         , 'UserController@uploadAvatarUsers');

Route::post('login'  , 'AuthController@login');
Route::post('logout' , 'AuthController@logout');

Route::apiResource('barbers'      , 'BarbersController');
Route::post('barbers/avatar/{id}' , 'BarbersController@uploadAvatarBarber');
Route::apiResource('schedules'    , 'ScheduleController');

Route::get('barbers/{id}/agendamentos','ScheduleController@pesquisarAgendamentoBarber');

Route::post('barbearias/{id}/feedbacks' , 'FeedbackController@store');
Route::get('barbearias/{id}/feedbacks'  , 'FeedbackController@ShowAllFeedbacksBarberShop');
