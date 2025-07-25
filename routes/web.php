<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntresController;
use App\Http\Controllers\SortiesController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Page d'accueil
Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [SortiesController::class, 'statistiques'])->name('dashboard');
});






Route::get('/check-plaque', function (Request $request) {
    $exists = \App\Models\Entres::where('plaque', $request->plaque)
                ->whereNull('sortie_at') // Si pas encore sorti
                ->exists();
    return response()->json(['exists' => $exists]);
});

Route::get('/sorties/ticket/{id}', [SortiesController::class, 'ticket'])->name('sorti-ticket');
Route::get('/sorties/ticket/download/{id}', [SortiesController::class, 'downloadTicket'])->name('ticket.download');


Route::get('/vehicule/info', [App\Http\Controllers\EntresController::class, 'getVehiculeInfo'])->name('vehicule.info');

Route::get('/export/jour', [SortiesController::class, 'exportJour'])->name('export.jour');
Route::get('/export/semaine', [SortiesController::class, 'exportSemaine'])->name('export.semaine');
Route::get('/export/mois', [SortiesController::class, 'exportMois'])->name('export.mois');
Route::get('/export/annee', [SortiesController::class, 'exportAnnee'])->name('export.annee');


// Tableau de bord (statistiques)


// Activités récentes (accessible sans auth si besoin)
Route::middleware('auth')->group(function () {
Route::get('/recent', [ActivityController::class, 'activites'])->name('recent');
});
// Entrées véhicules (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/entres', [EntresController::class, 'create'])->name('entres.create');
    Route::post('/entres', [EntresController::class, 'store'])->name('entres.store');
});

// Sorties véhicules (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/sorties', [SortiesController::class, 'create'])->name('sorties.create');
    Route::post('/sorties', [SortiesController::class, 'store'])->name('sorties.store');
});

// Profil utilisateur (auth requis)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth routes (register, login, etc.)
require __DIR__.'/auth.php';
