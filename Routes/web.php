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

/*
 * ADMIN ROUTES
 */

use Illuminate\Support\Facades\Route;
use Modules\Logos\Http\Controllers\LogosController;

/* Logos Admin */
Route::group(['prefix' => 'admins', 'middleware' => ['auth']], static function () {
    Route::group(['prefix' => 'logos'], static function () {
        Route::get('/', [LogosController::class, 'index'])->name('admin.logos.index');
        Route::post('/get-path', [LogosController::class, 'getEncryptedPath'])->name('admin.logos.manage.get-path');
        Route::get('/load-logos/{path}', [LogosController::class, 'loadIconsPage'])->name('admin.logos.manage.load-logos');
        Route::get('/create/{path}', [LogosController::class, 'create'])->name('admin.logos.create');
        Route::post('/store/{path}', [LogosController::class, 'store'])->name('admin.logos.store');
        Route::get('to_many_pages', [LogosController::class, 'toManyPagesCreate'])->name('admin.logos.toManyPagesCreate');
        Route::post('/storeToManyPages', [LogosController::class, 'storeToManyPages'])->name('admin.logos.storeToManyPages');

        Route::group(['prefix' => 'multiple'], static function () {
            Route::get('active/{active}', [LogosController::class, 'activeMultiple'])->name('admin.logos.active-multiple');
            Route::get('delete', [LogosController::class, 'deleteMultiple'])->name('admin.logos.delete-multiple');
        });

        Route::group(['prefix' => '{id}'], static function () {
            Route::get('edit', [LogosController::class, 'edit'])->name('admin.logos.edit');
            Route::post('update', [LogosController::class, 'update'])->name('admin.logos.update');
            Route::get('delete', [LogosController::class, 'delete'])->name('admin.logos.delete');
            Route::get('show', [LogosController::class, 'show'])->name('admin.logos.show');
            Route::get('/active/{active}', [LogosController::class, 'active'])->name('admin.logos.changeStatus');
            Route::get('position/up', [LogosController::class, 'positionUp'])->name('admin.logos.position-up');
            Route::get('position/down', [LogosController::class, 'positionDown'])->name('admin.logos.position-down');
        });
    });
  });
