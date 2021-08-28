<?php

use App\Models\SubmittedTest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/change/status/{test_id}', function($test_id) {
    $submittedTest = SubmittedTest::find($test_id);
    $submittedTest->selection_status = !$submittedTest->selection_status;
    $submittedTest->save();
})->name('status.change');
