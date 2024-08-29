<?php
use App\Http\Controllers\Auth\PasswordResetLinkController;
use Illuminate\Support\Facades\Route;


Route::get('reset-password/{token}', [PasswordResetLinkController::class, 'create'])
->name('password.reset');

Route::post('/password/reset', [PasswordResetLinkController::class, 'update'])
->name('password.update');

Route::post('/reset-password', [PasswordResetLinkController::class, 'store'])
    ->name('password.store');