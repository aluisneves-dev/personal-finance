<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FluxoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CredorController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartaoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LancamentoController;
use App\Http\Controllers\PagamentoController;

Route::middleware('auth')->group(function () {

    // CATEGORIA //
    Route::get('/categoria', function() { return view('categoria.index'); })->name('categoria');
    Route::any('/categoria/lista', [CategoriaController::class, 'index']);
    Route::post('/categoria', [CategoriaController::class, 'merge']);
    Route::get('/categoria/id={id}', [CategoriaController::class, 'view']);
    Route::get('/categoria/delete/{id}', [CategoriaController::class, 'destroy']);
    Route::get('/getCategoria/tipo={tipo}', [CategoriaController::class, 'getCategoria']);

    // CREDOR //
    Route::get('/credor', function() { return view('credor.index'); })->name('credor');
    Route::get('/credor/lista', [CredorController::class, 'index']);
    Route::post('/credor', [CredorController::class, 'merge']);
    Route::get('/credor/id={id}', [CredorController::class, 'view']);
    Route::get('/credor/delete/{id}', [CredorController::class, 'destroy']);

    // PAGAMENTO //
    Route::get('/pagamento', function() { return view('pagamento.index'); })->name('pagamento');
    Route::get('/pagamento/lista', [PagamentoController::class, 'index']);
    Route::post('/pagamento', [PagamentoController::class, 'merge']);
    Route::get('/pagamento/id={id}', [PagamentoController::class, 'view']);
    Route::get('/pagamento/delete/{id}', [PagamentoController::class, 'destroy']);

    // INDEX //
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::any('/fluxo', [FluxoController::class, 'index'])->name('fluxo');
    Route::get('/lancamentos', function(){ return view('lancamento.index'); });
    Route::get('lancamentos/lista', [LancamentoController::class, 'index']);

    // MERGE //
    Route::post('/lancamentos', [LancamentoController::class, 'merge']);

    // DESTROY //
    Route::get('/lancamentos/delete/{id}', [LancamentoController::class, 'destroy']);

    // LANCAMENTOS ESPECÍFICOS //
    Route::get('/lancamentos/id={id}', [LancamentoController::class, 'view']);
    Route::get('/lancamentos/clone/{id}', [LancamentoController::class, 'clone']);

    // FLUXO //
    Route::post('fluxoEdit', [FluxoController::class, 'getLancamento']);
    Route::post('/lancamentosFluxo', [LancamentoController::class, 'mergeFluxo']);

    // CARTÃO DE CRÉDITO //
    Route::get('/cartao', function(){ return view('cartao.index'); });
    Route::get('cartao/lista', [CartaoController::class, 'index']);
    Route::post('/cartao/view', [CartaoController::class, 'getCartao']);

    // DASHBOARD //
    Route::post('/dashboard/view/categoria', [DashboardController::class, 'getCategoria']);

    // LOGOUT //
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// ROTA PARA VISITANTES //
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::post('/processaForm', [ContactController::class, 'sendEmail']);
    Route::get('/processaForm', [ContactController::class, 'sendEmail']);
});