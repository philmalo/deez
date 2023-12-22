<?php
//* Controlleurs de Laravel
use Illuminate\Support\Facades\Route;
//* Controlleur Scraper
use App\Http\Controllers\ScraperController;
//* Controlleur Sprint0
use App\Http\Controllers\Sprint0Controller;
//* Controlleurs Normaux
use App\Http\Controllers\UserController;
use App\Http\Controllers\CellierController;
use App\Http\Controllers\BouteilleController;
use App\Http\Controllers\CellierQuantiteBouteilleController;
use App\Http\Controllers\CommentaireBouteilleController;
use App\Http\Controllers\ListeAchatController;
//* Controlleurs Admin
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCellierController;
use App\Http\Controllers\AdminBouteilleController;
use App\Http\Controllers\AdminBouteillePersonnaliseeController;
//* Relatif a Breeze
use App\Http\Controllers\ProfileController;
//* Relatif a Glide
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
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

Route::get('/humans.txt', function () {
    return response(file_get_contents(public_path('humans.txt')), 200)
        ->header('Content-Type', 'text/plain; charset=UTF-8');
})->name('humans.txt');

Route::group(
[
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function()
{
    Route::get('/', function () {
        return view('auth.login');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
    //* SECTION SCRAPER
    Route::prefix('scraper')->name('scraper.')->group(function () {
        Route::get('/', [ScraperController::class, 'index'])->name('index');
        Route::get('/welcome', [ScraperController::class, 'welcome'])->name('welcome');
        Route::get('/keywords', [ScraperController::class, 'keywords'])->name('keywords');
        Route::get('/codes', [ScraperController::class, 'codes'])->name('codes');
        Route::get('/liste', [ScraperController::class, 'liste'])->name('liste');
    });

    //* SECTION SPRINT0
    Route::get('/sprint0', [Sprint0Controller::class, 'demoListe'])->name('sprint0.liste');

    //* SECTION ADMIN 
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::resource('bouteilles', AdminBouteilleController::class);
        // Route::resource('stats', AdminUserController::class)->names(['index' => 'admin.stats.index', 'destroy' => 'admin.stats.destroy']);
        Route::get('stats', [AdminUserController::class, 'index'])->name('admin.index');
        // Route::resource('celliers', AdminCellierController::class);
        Route::get('celliers', [AdminUserController::class, 'celliers'])->name('admin.celliers');

        Route::get('users', [AdminUserController::class, 'users'])->name('admin.users');
        Route::patch('users/{user}', [AdminUserController::class, 'update'])->name('admin.update');

        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('admin.destroy');

        Route::resource('bouteilles_personnalisees', AdminBouteillePersonnaliseeController::class);
    });

    //* SECTION APPLICATION DEEZ_WINES
    //Route::get('bouteilles/search', [BouteilleController::class, 'search'])->name('bouteilles.search');
    Route::resource('bouteilles', BouteilleController::class);
    Route::resource('users', UserController::class);
    Route::resource('celliers', CellierController::class);
    Route::resource('bouteilles_personnalisees', BouteillePersonnaliseeController::class);
    Route::resource('cellier_quantite_bouteille', CellierQuantiteBouteilleController::class);
    Route::resource('commentaire_bouteille', CommentaireBouteilleController::class);
    Route::resource('liste_achat', ListeAchatController::class);


    //* SECTION GLIDE (manipulation d'images)
    Route::get('glide/{path}', function ($path) {
        $server = ServerFactory::create([
            'response' => new LaravelResponseFactory(),
            'source' => storage_path('app'), // Chemin de la source des images originales
            'cache' => storage_path('app/glide'), // Chemin du cache des images manipulées
            'base_url' => '',
            'presets' => [
                'detail' => [
                    'w' => 360,
                    'h' => 540,
                    'fit' => 'crop',
                ],
                'maquette' => [
                    // 'w' => 50,
                    'h' => 145,
                    'fit' => 'crop',
                ],
                'xs' => [
                    'w' => 100,
                    'h' => 150,
                    'fit' => 'crop',
                ],
                'sm' => [
                    'w' => 320,
                    'h' => 240,
                    'fit' => 'crop',
                ],
                'md' => [
                    'w' => 640,
                    'h' => 480,
                    'fit' => 'crop',
                ],
                'lg' => [
                    'w' => 800,
                    'h' => 600,
                    'fit' => 'crop',
                ],
                'xl' => [
                    'w' => 1024,
                    'h' => 768,
                    'fit' => 'crop',
                ],
            ],
        ]);

        $params = request()->all();

        return $server->getImageResponse($path, $params);
    })->where('path', '.*');
});