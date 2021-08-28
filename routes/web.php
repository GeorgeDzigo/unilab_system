<?php
use App\Http\Controllers\StudentTestController;

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
    return redirect('/admin/login');
});

Route::get('test', 'TestController@test');

Route::prefix('/users/export/')->group(function() {
    Route::get('/{competition_id}', [\App\Http\Controllers\Admin\SubmittedTestsController::class, 'exportByCompetition'])->name('tests.export.competition');
    Route::get('/{department_id}/{competition_id}', [\App\Http\Controllers\Admin\SubmittedTestsController::class, 'exportByDepartment'])->name('tests.export.department');
});

Route::prefix('/test')->name('test.')->group(function() {
    Route::get('/success', [StudentTestController::class, 'success'])->name('success');

    Route::get('/{slug}', [StudentTestController::class, 'index'])->name('show');
    Route::post('/{slug}', [StudentTestController::class, 'store'])->name('save');

});

Route::get('/submitted/{slug}', function($slug)  {
    return view('test.already_registered');
})->name('alreadySubmitted');
//
//Route::group([
//    'prefix'     => config('backpack.base.route_prefix', 'admin'),
//    'middleware' => [
//        config('backpack.base.web_middleware', 'web'),
//        config('backpack.base.middleware_key', 'admin'),
//    ],
//    'namespace'  => 'Client',
//], function () {
//
//
//    /**
//     * Items manage.
//     */
//    Route::get('items/manage', 'ItemsController@manage')
//            ->name('item.manage');
//
//
//});
