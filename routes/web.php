<?php

use App\Exports\CourseExport;
use App\Http\Controllers\AuthController as AuthControllerAlias;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePersonController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group(["middleware" => "guest"], function () {
    Route::get('/', function () {
        return view('auth.login');
    });
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/processLogin', [AuthControllerAlias::class, "login"])->name('processLogin');
});

Route::middleware(["auth", "web"])->post('/logout', [AuthControllerAlias::class, 'logout'])->name('logout');

Route::prefix('person')->name('person.')->middleware(["auth", "web", "role:user"])->group(function () {
    Route::get('/', [PersonController::class, 'index'])->name('list');
    Route::get('/export-excel', [PersonController::class, 'export_excel'])->name('export');
});
Route::prefix('person')->name('person.')->middleware(["auth", "web", "role:writer"])->group(function () {
    Route::get('/add', [PersonController::class, 'create'])->name('insert');
    Route::post('/api', [PersonController::class, 'apiCourse'])->name('api');
    Route::post('/store', [PersonController::class, 'store'])->name('store');
    Route::post('/mail', [PersonController::class, 'checkMail'])->name('mail');
    Route::get('/edit/{person}', [PersonController::class, 'edit'])->name('edit');
    Route::put('/edit/{person}', [PersonController::class, 'update'])->name('update');
    Route::get('/get', [PersonController::class, 'get'])->name('get');
});

Route::prefix('person')->name('person.')->middleware(["auth", "web", "role:admin"])->group(function () {
    Route::delete('/destroy/{person}', [PersonController::class, 'destroy'])->name('destroy')->middleware(['role:admin']);
});

Route::prefix('course')->name('course.')->middleware(["auth", "web", "role:user"])->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('list');
    Route::get('/export-excel', [CourseController::class, 'export_excel'])->name('export');
});


Route::prefix('course')->name('course.')->middleware(["auth", "web", "role:writer"])->group(function () {
    Route::get('/add', [CourseController::class, 'create'])->name('insert');
    Route::post('/store', [CourseController::class, 'store'])->name('store');
    Route::get('/get', [CourseController::class, 'get'])->name('get');
    Route::get('/edit/{course}', [CourseController::class, 'edit'])->name('edit');
    Route::put('/edit/{course}', [CourseController::class, 'update'])->name('update');
    Route::post('/import-excel', [CourseController::class, 'import_excel'])->name('import');
    Route::post('/api', [CourseController::class, 'apiPerson'])->name('api');
});

Route::prefix('course')->name('course.')->middleware(["auth", "web", "role:admin"])->group(function () {
    Route::delete('/destroy/{course}', [CourseController::class, 'destroy'])->name('destroy');
});

Route::prefix('course_person')->name('course_person.')->middleware(["auth", "web", "role:writer"])->group(function () {
    Route::post('/store', [CoursePersonController::class, 'store'])->name('store');
    Route::post('/store-list/{course}', [CoursePersonController::class, 'store_list'])->name('store_list');
});

Route::prefix('course_person')->name('course_person.')->middleware(["auth", "web", "role:admin"])->group(function () {
    Route::post('/remove', [CoursePersonController::class, 'remove'])->name('remove');
});
