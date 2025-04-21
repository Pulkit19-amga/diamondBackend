<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\DiamondMaster\DiamondCutController;
use App\Http\Controllers\DiamondMaster\DiamondSizeController;
use App\Http\Controllers\DiamondMaster\DiamondCuletController;
use App\Http\Controllers\DiamondMaster\DiamondShadeController;
use App\Http\Controllers\DiamondMaster\DiamondShapeController;
use App\Http\Controllers\DiamondMaster\DiamondGirdleController;
use App\Http\Controllers\DiamondMaster\DiamondVendorController;
use App\Http\Controllers\DiamondMaster\DiamondSymmetryController;
use App\Http\Controllers\DiamondMaster\DiamondLabMasterController;
use App\Http\Controllers\DiamondMaster\DiamondFancyColorController;
use App\Http\Controllers\DiamondMaster\DiamondColorMasterController;
use App\Http\Controllers\DiamondMaster\DiamondFlourescenceController;
use App\Http\Controllers\DiamondMaster\DiamondPolishMasterController;
use App\Http\Controllers\DiamondMaster\DiamondClarityMasterController;
use App\Http\Controllers\DiamondMaster\DiamondKeyToSymbolsMasterController;
use App\Http\Controllers\DiamondMaster\DiamondFancyColorIntensityMasterController;
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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // welcome page
    })->name('admin.dashboard');

    Route::controller(DiamondClarityMasterController::class)->group(function () {
        Route::get('/clarity', 'index')->name('clarity.index');
        Route::post('/clarity', 'store')->name('clarity.store');
        Route::get('/clarity/{id}', 'show')->name('clarity.show'); 
        Route::put('/clarity/{id}', 'update')->name('clarity.update');
        Route::delete('/clarity/{id}', 'destroy')->name('clarity.destroy');
    });

    Route::controller(DiamondShadeController::class)->group(function () {
        Route::get('/shades', 'index')->name('shades.index');
        Route::post('/shades', 'store')->name('shades.store');
        Route::get('/shades/{id}', 'show')->name('shades.show'); 
        Route::put('/shades/{id}', 'update')->name('shades.update');
        Route::delete('/shades/{id}', 'destroy')->name('shades.destroy');
    });

    Route::controller(DiamondShapeController::class)->group(function () {
        Route::get('/shapes', 'index')->name('shapes.index');
        Route::post('/shapes', 'store')->name('shapes.store');
        Route::get('/shapes/{id}', 'show')->name('shapes.show'); 
        Route::put('/shapes/{id}', 'update')->name('shapes.update');
        Route::delete('/shapes/{id}', 'destroy')->name('shapes.destroy');    
    });
    
    Route::controller(DiamondSizeController::class)->group(function () {
        Route::get('/sizes', 'index')->name('sizes.index');
        Route::post('/sizes', 'store')->name('sizes.store');
        Route::get('/sizes/{id}', 'show')->name('sizes.show'); 
        Route::put('/sizes/{id}', 'update')->name('sizes.update');
        Route::delete('/sizes/{id}', 'destroy')->name('sizes.destroy');    
    });

    Route::controller(DiamondSymmetryController::class)->group(function () {
        Route::get('/symmetry', 'index')->name('symmetry.index');
        Route::post('/symmetry', 'store')->name('symmetry.store');
        Route::get('/symmetry/{id}', 'show')->name('symmetry.show'); 
        Route::put('/symmetry/{id}', 'update')->name('symmetry.update');
        Route::delete('/symmetry/{id}', 'destroy')->name('symmetry.destroy');    
    });
    Route::controller(DiamondFlourescenceController::class)->group(function () {
        Route::get('/flourescence', 'index')->name('flourescence.index');
        Route::post('/flourescence', 'store')->name('flourescence.store');
        Route::get('/flourescence/{id}', 'show')->name('flourescence.show'); 
        Route::put('/flourescence/{id}', 'update')->name('flourescence.update');
        Route::delete('/flourescence/{id}', 'destroy')->name('flourescence.destroy');    
    });

    Route::controller(DiamondFancyColorController::class)->group(function () {
        Route::get('/fancyColor', 'index')->name('fancyColor.index');
        Route::post('/fancyColor', 'store')->name('fancyColor.store');
        Route::get('/fancyColor/{id}', 'show')->name('fancyColor.show'); 
        Route::put('/fancyColor/{id}', 'update')->name('fancyColor.update');
        Route::delete('/fancyColor/{id}', 'destroy')->name('fancyColor.destroy');    
    });

    Route::controller(DiamondGirdleController::class)->group(function () {
        Route::get('/girdle', 'index')->name('girdle.index');
        Route::post('/girdle', 'store')->name('girdle.store');
        Route::get('/girdle/{id}', 'show')->name('girdle.show'); 
        Route::put('/girdle/{id}', 'update')->name('girdle.update');
        Route::delete('/girdle/{id}', 'destroy')->name('girdle.destroy');    
    });

    Route::controller(DiamondCuletController::class)->group(function () {
        Route::get('/culet', 'index')->name('culet.index');
        Route::post('/culet', 'store')->name('culet.store');
        Route::get('/culet/{id}', 'show')->name('culet.show'); 
        Route::put('/culet/{id}', 'update')->name('culet.update');
        Route::delete('/culet/{id}', 'destroy')->name('culet.destroy');    
    });
    
    Route::controller(DiamondKeyToSymbolsMasterController::class)->group(function () {
        Route::get('/keyToSymbols', 'index')->name('keytosymbols.index');
        Route::get('/keyToSymbols/create', 'create')->name('keytosymbols.create');
        Route::get('/keyToSymbols/{id}/edit', 'edit')->name('keytosymbols.edit');
        Route::get('/keyToSymbols/{id}', 'show')->name('keytosymbols.show');
        Route::post('/keyToSymbols', 'store')->name('keytosymbols.store');
        Route::put('/keyToSymbols/{id}', 'update')->name('keytosymbols.update');
        Route::delete('/keyToSymbols/{id}', 'destroy')->name('keytosymbols.destroy');
    });

    Route::controller(DiamondLabMasterController::class)->group(function () {
        Route::get('/lab', 'index')->name('diamondlab.index');
        Route::get('/lab/create', 'create')->name('diamondlab.create');
        Route::post('/lab', 'store')->name('diamondlab.store');
        Route::get('/lab/{id}/edit', 'edit')->name('diamondlab.edit');
        Route::get('/lab/{id}', 'show')->name('diamondlab.show');
        Route::put('/lab/{id}', 'update')->name('diamondlab.update');
        Route::delete('/lab/{id}', 'destroy')->name('diamondlab.destroy');
    });

    Route::controller(DiamondPolishMasterController::class)->group(function () {
        Route::get('/polish', 'index')->name('diamondpolish.index');
        Route::post('/polish', 'store')->name('diamondpolish.store');
        Route::get('/polish/{id}', 'show')->name('diamondpolish.show');
        Route::put('/polish/{id}', 'update')->name('diamondpolish.update');
        Route::delete('/polish/{id}', 'destroy')->name('diamondpolish.destroy');
    });
    Route::controller(DiamondColorMasterController::class)->group(function() {
        Route::get('diamond-color', 'index')->name('color.index');
        Route::post('diamond-color', 'store')->name('color.store');
        Route::get('diamond-color/{id}', 'show');
        Route::put('diamond-color/{id}', 'update');
        Route::delete('diamond-color/{id}', 'destroy');
    });

    Route::controller(DiamondCutController::class)->group(function () {
        Route::get('/cut', 'index')->name('cut.index');
        Route::post('/cut', 'store')->name('cut.store');
        Route::get('/cut/{id}', 'show')->name('cut.show'); 
        Route::put('/cut/{id}', 'update')->name('cut.update');
        Route::delete('/cut/{id}', 'destroy')->name('cut.destroy');    
    });

    Route::controller(DiamondFancyColorIntensityMasterController::class)->group(function() {
        Route::get('/diamond-fancy-color-intensity', 'index')->name('fancy-color-intensity.index');
        Route::post('/diamond-fancy-color-intensity', 'store')->name('fancy-color-intensity.store');
        Route::get('/diamond-fancy-color-intensity/{id}', 'show')->name('fancy-color-intensity.show');
        Route::put('/diamond-fancy-color-intensity/{id}', 'update')->name('fancy-color-intensity.update');
        Route::delete('/diamond-fancy-color-intensity/{id}', 'destroy')->name('fancy-color-intensity.destroy');
    });

    Route::controller(DiamondVendorController::class)->group(function() {
        Route::get('/vendor',        'index')->name('vendor.index');
        Route::post('/vendor',       'store')->name('vendor.store');
        Route::get('/vendor/{id}',   'show')->name('vendor.show');
        Route::put('/vendor/{id}',   'update')->name('vendor.update');
        Route::delete('/vendor/{id}','destroy')->name('vendor.destroy');
    });
});
