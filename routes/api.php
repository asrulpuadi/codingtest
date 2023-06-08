<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GenerateMovieController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WatchListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


//auth passport
Route::post('/login', [AuthController::class, 'Login']);
Route::post('/register', [AuthController::class, 'Register']);

Route::get('/user', [UserController::class, 'GetUserInfo'])->middleware('auth:api');

Route::post('/logout', [AuthController::class, 'LogOut'])->middleware('auth:api');

//route insert movie to own database
Route::get('/generatgenreemovie', [GenerateMovieController::class, 'GenerateGenreMovie']);

//route all genre
Route::get('/allgenre', [MovieController::class, 'AllGenre']);

//route get list movie by genre
Route::get('/movielist/genre/{id}', [MovieController::class, 'MovieByGenre']);

//route get detail movie
Route::get('/detailmovie/{movie_id}', [MovieController::class, 'DetailMovie']);

//route watch list
Route::get('/watchlist', [WatchListController::class, 'WatchList'])->middleware('auth:api');
Route::post('/addtowatchlist', [WatchListController::class, 'AddtoWatchList'])->middleware('auth:api');
Route::get('/watchlistbyid/{id}', [WatchListController::class, 'WatchListById'])->middleware('auth:api');
Route::post('/updatenote', [WatchListController::class, 'UpdateWatchList'])->middleware('auth:api');
Route::get('/deletewatchlist/{id}', [WatchListController::class, 'DeleteWatchList'])->middleware('auth:api');


//route report
Route::get('/reportuserregis', [ReportController::class, 'ReportUserRegis']);
Route::get('/avgwatchlistbyday', [ReportController::class, 'AvgWatchListByDay']);
Route::get('/totaladdwatchlist', [ReportController::class, 'TotalAddWatchList']);
Route::get('/reportmonthlyrank', [ReportController::class, 'MonthlyRank']);
