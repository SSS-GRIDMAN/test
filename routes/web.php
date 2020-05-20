<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::post('/api/admin/user', 'AdminController@getUsers');
Route::post('/api/admin/assignment', 'AdminController@getAssignments');
Route::post('/api/adin/group', 'AdminController@getGroups');
Route::post('/form', 'StudentController@assignmentUpload');
Route::get('/formDown', 'StudentController@dowloadAssignmentRequire');
// Route::post();
