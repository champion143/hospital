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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/hospital', [App\Http\Controllers\HomeController::class, 'hospital'])->name('hospital');

Route::get('/addhospital', [App\Http\Controllers\HomeController::class, 'addhospital'])->name('addhospital');

Route::post('/addactionhospital', [App\Http\Controllers\HomeController::class, 'addactionhospital'])->name('addactionhospital');

Route::post('/deletehospital', [App\Http\Controllers\HomeController::class, 'deletehospital'])->name('deletehospital');

Route::get('/editHospital/{id}', [App\Http\Controllers\HomeController::class, 'editHospital'])->name('editHospital');

Route::post('/updatehospital', [App\Http\Controllers\HomeController::class, 'updatehospital'])->name('updatehospital');


Route::get('/department', [App\Http\Controllers\HomeController::class, 'department'])->name('department');

Route::get('/adddepartment', [App\Http\Controllers\HomeController::class, 'adddepartment'])->name('adddepartment');

Route::post('/addactiondepartment', [App\Http\Controllers\HomeController::class, 'addactiondepartment'])->name('addactiondepartment');

Route::post('/deletedepartment', [App\Http\Controllers\HomeController::class, 'deletedepartment'])->name('deletedepartment');

Route::get('/editdepartment/{id}', [App\Http\Controllers\HomeController::class, 'editdepartment'])->name('editdepartment');

Route::post('/updatedepartment', [App\Http\Controllers\HomeController::class, 'updatedepartment'])->name('updatedepartment');


Route::get('/facility', [App\Http\Controllers\HomeController::class, 'facility'])->name('facility');

Route::get('/addfacility', [App\Http\Controllers\HomeController::class, 'addfacility'])->name('addfacility');

Route::post('/addactionfacility', [App\Http\Controllers\HomeController::class, 'addactionfacility'])->name('addactionfacility');

Route::post('/deletefacility', [App\Http\Controllers\HomeController::class, 'deletefacility'])->name('deletefacility');

Route::get('/editfacility/{id}', [App\Http\Controllers\HomeController::class, 'editfacility'])->name('editfacility');

Route::post('/updatefacility', [App\Http\Controllers\HomeController::class, 'updatefacility'])->name('updatefacility');



Route::get('/doctor', [App\Http\Controllers\HomeController::class, 'doctor'])->name('doctor');

Route::get('/adddoctor', [App\Http\Controllers\HomeController::class, 'adddoctor'])->name('adddoctor');

Route::post('/addactiondoctor', [App\Http\Controllers\HomeController::class, 'addactiondoctor'])->name('addactiondoctor');

Route::post('/deletedoctor', [App\Http\Controllers\HomeController::class, 'deletedoctor'])->name('deletedoctor');

Route::get('/editdoctor/{id}', [App\Http\Controllers\HomeController::class, 'editdoctor'])->name('editdoctor');

Route::post('/updatedoctor', [App\Http\Controllers\HomeController::class, 'updatedoctor'])->name('updatedoctor');


Route::get('/tag', [App\Http\Controllers\HomeController::class, 'tag'])->name('tag');

Route::post('/addactiontag', [App\Http\Controllers\HomeController::class, 'addactiontag'])->name('addactiontag');

Route::post('/deletetag', [App\Http\Controllers\HomeController::class, 'deletetag'])->name('deletetag');

Route::get('/getTag', [App\Http\Controllers\HomeController::class, 'getTag'])->name('getTag');
