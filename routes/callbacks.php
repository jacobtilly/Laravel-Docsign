<?php

use Illuminate\Support\Facades\Route;
use JacobTilly\LaravelDocsign\Http\Controllers\CallbackController;

Route::prefix('docsign/callbacks')->group(function () {
    Route::get('/document-complete', [CallbackController::class, 'documentComplete'])->name("docsign.callbacks.document-complete");
    Route::get('/party-sign', [CallbackController::class, 'partySign'])->name("docsign.callbacks.party-sign");
});
